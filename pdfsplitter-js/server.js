import dotenv from 'dotenv'
import express from 'express'


dotenv.config()
const app = express()
const port = process.env.PORT

app.post('/pdf-splitter', (req, res) => {

})


app.listen(port)
