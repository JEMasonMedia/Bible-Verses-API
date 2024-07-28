const express = require('express')
const fs = require('fs')
const crypto = require('crypto')
const path = require('path')
const cors = require('cors') // Import the cors package

const app = express()
const port = 3000

// Enable CORS for all routes
app.use(cors())

// Serve the HTML file at the root
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'nodeIndex.html'))
})

// Read the JSON file
const versesFilePath = path.join(__dirname, 'verses.json')
let verses = []

try {
  const data = fs.readFileSync(versesFilePath, 'utf8')
  verses = JSON.parse(data)
} catch (err) {
  console.error('Failed to read or parse verses.json:', err)
}

function cryptoRandomNumberInRange(min, max) {
  const range = max - min + 1
  const randomBytes = crypto.randomBytes(4)
  const randomNumber = randomBytes.readUInt32LE(0) / (0xffffffff + 1)
  return Math.floor(randomNumber * range) + min
}

function betterRandom(min, max, depth = 1000) {
  const accumulator = []

  for (let i = 0; i < depth; i++) {
    accumulator.push(cryptoRandomNumberInRange(min, max))
  }

  const randomIndex = cryptoRandomNumberInRange(0, accumulator.length - 1)
  return accumulator[randomIndex]
}

function getRandomVerse(verses) {
  const min = 0
  const max = verses.length - 1
  if (max < min) {
    return null
  }
  const randomIndex = betterRandom(min, max, 10000)
  return verses[randomIndex]
}

function getVerseById(verses, id) {
  return verses.find(verse => verse.id === id) || null
}

function getVerseByReference(verses, book, chapter, verseNumber) {
  return verses.find(verse => verse.book === book && verse.chapter === chapter && verse.verse === verseNumber) || null
}

app.get('/verse', (req, res) => {
  const { id, book, chapter, verse, format, all } = req.query

  if (all === 'true') {
    res.json(verses)
    return
  }

  let verseData

  if (id) {
    verseData = getVerseById(verses, parseInt(id, 10))
  } else if (book && chapter && verse) {
    verseData = getVerseByReference(verses, book, parseInt(chapter, 10), parseInt(verse, 10))
  } else {
    verseData = getRandomVerse(verses)
  }

  if (!verseData) {
    res.status(404).json({ error: 'Verse not found' })
    return
  }

  if (format === 'plain') {
    res.send(`${verseData.id}\n${verseData.book}\n${verseData.chapter}\n${verseData.verse}\n${verseData.text}`)
  } else {
    res.json(verseData)
  }
})

app.get('/verses', (req, res) => {
  res.json(verses)
})

app.listen(port, () => {
  console.log(`Bible Verses API running at http://localhost:${port}`)
})
