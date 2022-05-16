import {PDFDocument} from 'pdf-lib'
import fs from 'fs/promises'
import path from 'path'
import yargs from 'yargs'
import {hideBin} from 'yargs/helpers'
import pdfpic from 'pdf2pic'


// если директория не существует создает иначе ничего не создает
async function pathExistsOrCreate(path) {
    try {
        await fs.access(path)
        return 'exists'
    } catch (e) {
        await fs.mkdir(path)
        return 'create'
    }
}

// диапазон массивов
function range(start, end) {
    let foos = [];
    for (let i = start; i <= end; i++) {
        foos.push(i);
    }
    return foos;
}

// сохраняет отрывок книги
async function pdfDocumentExcerpt({ filename, bookPdf, totalPages, pdfPagePath, excerptPageCount = 20 }) {

    let excerptPages = range(0, Number(excerptPageCount))

    if(Number(excerptPageCount) > Number(totalPages)) {
        excerptPages = range(0, Number(totalPages)-1)
    }

    //====================================================//
    const newPdfPage = await PDFDocument.create()
    const copiedPages = await newPdfPage.copyPages(bookPdf, excerptPages)
    excerptPages.forEach((index) => {
        newPdfPage.addPage(copiedPages[index])
    })
    const pageBytes = await newPdfPage.save()
    const newFileName = `${filename}-excerpt.pdf`
    await fs.writeFile(`${pdfPagePath}/${newFileName}`, pageBytes)
    //====================================================//
}

// разделяет pdf постранично на 1
async function pdfDocumentSplitter(pdfDocOptions) {
    // массив страниц документа
    const pages = pdfDocOptions.pages

    // кол-во страниц документа
    const totalPages = pdfDocOptions.totalPages

    // кол-во страниц документа массив
    const pagesArray = pdfDocOptions.pagesArray

    // директория документа
    const dirname = pdfDocOptions.dirname

    // директория для хранения страницы
    const pdfPagesPath = pdfDocOptions.pdfPagesPath

    // создание директорию pages если его не существует
    await pathExistsOrCreate(pdfPagesPath)

    //====================================================//
    // циклично сохраняем каждую страницу в отдельный файл
    let index = 0
    for(let i of pagesArray) {
        const newPdfPage = await PDFDocument.create()
        const copiedPages = await newPdfPage.copyPages(pdfDocOptions.pdfDoc, [index])
        newPdfPage.addPage(copiedPages[0])
        const pageBytes = await newPdfPage.save()
        const filename = `${index+1}.pdf`
        await fs.writeFile(`${pdfPagesPath}/${filename}`, pageBytes)
        index++
    }
    return index
}

async function getPdfDocument(filePath) {
    // папка для хранения страниц документа
    const pagesPath = '/pages'

    // директория для хранения страницы
    const dirname = path.dirname(filePath)

    // директория
    const pdfPagesPath = path.join(dirname, pagesPath)

    // читаем документ из папки
    const pdfFileBuffer = await fs.readFile(filePath)

    // читаем файл как pdf документ
    const pdfDoc = await PDFDocument.load(pdfFileBuffer)

    // массив страниц документа
    const pages = pdfDoc.getPages()

    // кол-во страниц документа
    const totalPages = pages.length

    // кол-во страниц документа массива
    const pagesArray = new Array(totalPages)

    return {
        filePath,
        dirname: path.dirname(filePath),
        pdfPagesPath: path.join(dirname, pagesPath),
        pdfFileBuffer,
        pdfDoc,
        pages,
        totalPages,
        pagesArray
    }
}


// создание мета файла
async function createJSONMetaFIle(totalPages, filename) {
    const meta = {
        totalPages,
        filename
    }
    const dirname = path.dirname(filename)
    await fs.writeFile(path.join(dirname, 'meta.json'), JSON.stringify(meta))
}

// сохранить скриншот документа
async function saveDocumentCover(pdfDocOptions) {
    const options = {
        saveFilename: "cover",
        savePath: pdfDocOptions.dirname,
        format: "jpg",
        density: 330,
        width: 1024,
        height: 1325
    };
    const convert = pdfpic.fromBuffer(pdfDocOptions.pdfFileBuffer, options);
    const pageToConvertAsImage = 1;

    convert(pageToConvertAsImage).then(async (resolve) => {
        await fs.writeFile(
            path.join(pdfDocOptions.dirname, 'cover_log.json'),
            JSON.stringify({
                success: true,
                message: "cover image generated"
            })
        );
        return resolve;
    }).catch(async (e) => {
        await fs.writeFile(
            path.join(pdfDocOptions.dirname, 'cover_log.json'),
            JSON.stringify(e)
        );
    });
}

async function splitter(argv) {
    if (argv.filename) {
        // получение опции о документе
        const pdfDocOptions = await getPdfDocument(argv.filename)

        // сохранение и разделение на страницы документа
        const totalPages = await pdfDocumentSplitter(pdfDocOptions)

        // сохранение отрывка книги pdf
        await pdfDocumentExcerpt({
            filename: path.basename(pdfDocOptions.filePath),
            bookPdf: pdfDocOptions.pdfDoc,
            totalPages: pdfDocOptions.totalPages,
            pdfPagePath: pdfDocOptions.dirname,
            excerptPageCount: Number(argv['excerpt-page-count'])
        })

        // создание мета данных
        await createJSONMetaFIle(totalPages, argv.filename)
        await saveDocumentCover(pdfDocOptions)
        return totalPages + ' pages created'
    }
    return 'file path not passed'
}


const argv = yargs(hideBin(process.argv)).argv
splitter(argv).then(async (response) => {
    console.log(response)
    await fs.writeFile(
        path.join(path.dirname(argv.filename), 'log.json'),
        JSON.stringify(response)
    );
}).catch(async (err) => {
    console.log(err)
    await fs.writeFile(
        path.join(path.dirname(argv.filename), 'log.json'),
        JSON.stringify(err)
    );
})
