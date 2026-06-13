from flask import Flask, render_template, request, redirect, url_for
import sqlite3

app = Flask(__name__)

def get_db_connection():
    conn = sqlite3.connect('data.db')
    conn.row_factory = sqlite3.Row
    return conn

@app.route('/')
def index():
    conn = get_db_connection()
    items = conn.execute('SELECT * FROM gun').fetchall()
    conn.close()
    return render_template('list.html', items=items)

@app.route('/view/<int:id>')
def view(id):
    conn = get_db_connection()
    item = conn.execute('SELECT * FROM gun WHERE id = ?', (id,)).fetchone()
    conn.close()
    return render_template('view.html', item=item)

@app.route('/create', methods=('GET', 'POST'))
def create():
    if request.method == 'POST':
        name = request.form['name']
        caliber = request.form['caliber']
        magazine_capacity = request.form['magazine_capacity']

        conn = get_db_connection()
        conn.execute('INSERT INTO gun (name, caliber, magazine_capacity) VALUES (?, ?, ?)',
                     (name, caliber, magazine_capacity))
        conn.commit()
        conn.close()
        return redirect(url_for('index'))

    return render_template('create.html')

@app.route('/edit/<int:id>', methods=('GET', 'POST'))
def edit(id):
    conn = get_db_connection()
    item = conn.execute('SELECT * FROM gun WHERE id = ?', (id,)).fetchone()

    if request.method == 'POST':
        name = request.form['name']
        caliber = request.form['caliber']
        magazine_capacity = request.form['magazine_capacity']

        conn.execute('UPDATE gun SET name = ?, caliber = ?, magazine_capacity = ? WHERE id = ?',
                     (name, caliber, magazine_capacity, id))
        conn.commit()
        conn.close()
        return redirect(url_for('index'))

    conn.close()
    return render_template('edit.html', item=item)

@app.route('/delete/<int:id>', methods=('POST',))
def delete(id):
    conn = get_db_connection()
    conn.execute('DELETE FROM gun WHERE id = ?', (id,))
    conn.commit()
    conn.close()
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(debug=True, port=57744)