import { PDFDocument } from 'pdf-lib'
import fs from 'fs/promises'
import path from 'path'
import yargs from 'yargs'
import { hideBin } from 'yargs/helpers'

async function pathExistsOrCreate(path) {
    try {
        await fs.access(path)
        return 'exists'
    } catch (e) {
        await fs.mkdir(path)
        return 'create'
    }
}

async function pdfDocumentSplitter(filePath) {
    const pagesPath = '/pages'
    // читаем документ из папки
    const bookPdfFile = await fs.readFile(filePath)
    // читаем файл как pdf документ
    const bookPdf = await PDFDocument.load(bookPdfFile.buffer)

    // массив страниц документа
    const pages = bookPdf.getPages()
    // кол-во страниц документа
    const totalPages = pages.length
    const pagesArray = new Array(totalPages)

    // директория документа
    const dirname = path.dirname(filePath)
    // директория для хранения страницы
    const pdfPagesPath = path.join(dirname, pagesPath)
    // создание директорию pages
    await pathExistsOrCreate(pdfPagesPath)

    //====================================================//
    //====================================================//
    let index = 0
    for(let i of pagesArray) {
        const newPdfPage = await PDFDocument.create()
        const copiedPages = await newPdfPage.copyPages(bookPdf, [index])
        newPdfPage.addPage(copiedPages[0])
        const pageBytes = await newPdfPage.save()
        const filename = `${index+1}.pdf`
        await fs.writeFile(`${pdfPagesPath}/${filename}`, pageBytes)
        index++
    }
    //====================================================//
    return index
}

async function createJSONMetaFIle(totalPages, filename) {
    const meta = {
        totalPages,
        filename
    }
    const dirname = path.dirname(filename)
    await fs.writeFile(path.join(dirname, 'meta.json'), JSON.stringify(meta))
}

async function splitter(argv) {
    if (argv.filename) {
        const totalPages = await pdfDocumentSplitter(argv.filename)
        await createJSONMetaFIle(totalPages, argv.filename)
        return totalPages + ' pages created'
    }
    return 'file path not passed'
}

const argv = yargs(hideBin(process.argv)).argv
splitter(argv).then((response) => {
    console.log(response)
})
