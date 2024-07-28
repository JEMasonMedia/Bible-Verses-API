# Bible Verses API

This repository contains two versions of a simple Bible Verses API: one implemented in PHP and the other in Node.js with Express. Both versions serve random Bible verses or specific verses by ID and allow fetching all verses.

The verses are stored in a JSON file (`verses.json`) and are served as JSON responses. The PHP version uses a simple PHP script to serve the verses, while the Node.js version uses an Express server. The API endpoints are documented below.

The verses are taken from the King James Version (KJV) of the Bible and are stored in the following format:

```json
{
  "id": 1,
  "book": "Genesis",
  "chapter": 1,
  "verse": 1,
  "text": "In the beginning God created the heavens and the earth."
}
```

The `id` field is a unique identifier for each verse, and the `text` field contains the actual verse text. The `book`, `chapter`, and `verse` fields specify the book, chapter, and verse number of the verse, respectively.

The verses are stored in the `verses.json` file, which contains an array of verse objects. The PHP and Node.js versions of the API read this file to serve the verses.

When I get some time I am going to add a version in Python using Flask.

I hope soon this library will have the full bible, possibly several versions of the bible, which would be selectable by the user.

I hope you enjoy this library. You can see a live demo of the php version at [https://joelemason.com/bible_verses/](https://joelemason.com/bible_verses/). The Node.js version is essentially identical.

(The project will be better organized in the future.)

## PHP Version

### Setup

1. Ensure you have a web server with PHP support (e.g., Apache).
2. Place all files in your web server's directory.

### Endpoints

- **Random Verse**: `/Random_Verses_api.php`
- **Verse by ID**: `/Random_Verses_api.php?id={verseId}`
- **All Verses**: `/Random_Verses_api.php?all=true`

### Example Response

```json
{
  "id": 1,
  "book": "Genesis",
  "chapter": 1,
  "verse": 1,
  "text": "In the beginning God created the heavens and the earth."
}
```

## Node/Express Version

### Setup

1. Ensure you have a web server with Node support.
2. Place all files in a server directory.
3. Run `npm install` to install the required dependencies.
4. Run `npm run server` to start the server.

### Endpoints

- **Random Verse**: `http://localhost:3000/verse`
- **Verse by ID**: `http://localhost:3000/verse?id={verseId}`
- **All Verses**: `http://localhost:3000/verse?all=true`

### Example Response

```json
{
  "id": 1,
  "book": "Genesis",
  "chapter": 1,
  "verse": 1,
  "text": "In the beginning God created the heavens and the earth."
}
```
