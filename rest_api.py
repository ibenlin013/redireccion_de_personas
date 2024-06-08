from flask import Flask, jsonify
import csv
import mysql.connector
from datetime import datetime
import os

app = Flask(__name__)

# Configuración de la base de datos
db_config = {
    'user': 'ismael',
    'password': 'ismael',
    'host': 'localhost',
    'database': 'counting_db'
}

# Ruta del archivo log en la máquina host
log_file_path = './utils/data/logs/counting_data.csv'

def upload_logs():
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()

        with open(log_file_path, newline='') as csvfile:
            csvreader = csv.reader(csvfile)
            # Omitir la primera fila (encabezados)
            next(csvreader)

            for row in csvreader:
                move_in, date_in, move_out, date_out = row
                nodo_id = '1'
                # Manejo de campos vacíos
                if not date_in:
                    date_in = None
                if not date_out:
                    date_out = None
                # Convertir fecha y hora al formato adecuado
                date_in = datetime.strptime(date_in, "%Y-%m-%d:%H:%M") if date_in else None
                date_out = datetime.strptime(date_out, "%Y-%m-%d:%H:%M") if date_out else None
                cursor.execute("""
                    INSERT INTO logs (move_in, in_time, move_out, out_time,nodo_id) VALUES (%s, %s, %s, %s, %s)
                """, (move_in, date_in, move_out, date_out, nodo_id))

        conn.commit()
        cursor.close()
        return "Logs subidos correctamente", 200
    except Exception as e:
        return str(e), 500

@app.route('/upload_logs', methods=['POST'])
def upload_logs_endpoint():
    return upload_logs()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
