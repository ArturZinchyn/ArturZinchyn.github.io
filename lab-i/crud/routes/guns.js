var express = require('express');
var router = express.Router();
const { DatabaseSync } = require('node:sqlite');
const path = require('node:path');

const dbPath = path.resolve(__dirname, '..', 'data.db');
const db = new DatabaseSync(dbPath);

router.get('/', function(req, res, next) {
    try {
        const query = db.prepare('SELECT * FROM gun');
        const items = query.all();

        res.render('guns/list', {
            items: items
        });
    } catch (err) {
        next(err);
    }
});

router.get('/create', function(req, res, next) {
    res.render('guns/create');
});

router.post('/create', function(req, res, next) {
    try {
        const { name, caliber, magazine_capacity } = req.body;

        const result = db
            .prepare('INSERT INTO gun (name, caliber, magazine_capacity) VALUES (?, ?, ?)')
            .run(name, caliber, magazine_capacity);

        res.redirect('/guns/' + result.lastInsertRowid);
    } catch (err) {
        next(err);
    }
});

router.get('/:id', function(req, res, next) {
    try {
        const id = req.params.id;
        const query = db.prepare('SELECT * FROM gun WHERE id = ?');
        const item = query.get(id);

        res.render('guns/show', {
            item: item
        });
    } catch (err) {
        next(err);
    }
});

router.get('/:id/edit', function(req, res, next) {
    try {
        const id = req.params.id;
        const query = db.prepare('SELECT * FROM gun WHERE id = ?');
        const item = query.get(id);

        if (!item) {
            return res.status(404).send('Запис не знайдено');
        }

        res.render('guns/edit', {
            item: item
        });
    } catch (err) {
        next(err);
    }
});

router.post('/:id/edit', function(req, res, next) {
    try {
        const id = req.params.id;
        const { name, caliber, magazine_capacity } = req.body;

        db.prepare('UPDATE gun SET name = ?, caliber = ?, magazine_capacity = ? WHERE id = ?')
            .run(name, caliber, magazine_capacity, id);

        res.redirect('/guns');
    } catch (err) {
        next(err);
    }
});

router.post('/:id/delete', function(req, res, next) {
    try {
        const id = req.params.id;

        db.prepare('DELETE FROM gun WHERE id = ?').run(id);

        res.redirect('/guns');
    } catch (err) {
        next(err);
    }
});

module.exports = router;