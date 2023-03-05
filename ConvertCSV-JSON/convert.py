import csv
import json

CSVEncoding = 'UTF-8'

productsCSVFile = 'Productos.csv'
variationsCSVFile = "Variaciones.csv"

productsJSONFile = 'Productos.json'
variationsJSONFile = "Variaciones.json"
products = []
variations = []

# Obtenemos los productos desde el archivo CSV y lo transformamos a JSON
def ConvertProducts():
    with open(productsCSVFile, encoding=CSVEncoding) as csvFile:
        csvReader = csv.DictReader(csvFile)
        count = 0
        total_rows = sum(1 for row in csvReader) # Contamos el total de filas en el archivo CSV
        csvFile.seek(0) # Volvemos al inicio del archivo CSV para comenzar a leerlo desde el principio
        next(csvReader) # Saltamos la primera fila
        for row in csvReader:
            if row['ID']: # Solo procesamos las filas que tengan valor en la primera columna
                # Imprimimos la barra de carga
                count += 1
                progress = (count / total_rows) * 100
                print(f'Procesando fila {count} de {total_rows}... {progress:.2f}%')

                # Creamos un nuevo objeto para cada fila y lo añadimos a la lista

                m2Price = (float(row['PrecioM2']) if row['PrecioM2'] != "" else None)
                image = (row['Imagen'] if row['Imagen'] != "" else None)
                thumbnail  = (row['Miniatura'] if row['Miniatura'] != "" else None)
                category  = (row['Categoria'] if row['Categoria'] != "" else None)
                units = (int(row['Unidades']) if row['Unidades'] != "" else None)
                showUnits = (True if row['MostrarUnidades'].upper() == "SI" else False)
                m2ByUnit = (float(row['m2porBulto']) if row['m2porBulto'] != "" else None)
                variationID = (int(row['VariacionID']) if row['VariacionID'] != "" else None)

                item = {
                    'id': int(row['ID']),
                    'name': row['Nombre'],
                    'price': float(row['PrecioBulto']),
                    'm2Price': m2Price,
                    'description': row['Descripcion'],
                    'image': image,
                    'thumbnail': thumbnail,
                    'category': category,
                    'units': units,
                    'showUnits': showUnits,
                    'm2ByUnit': m2ByUnit,
                    'variationID': variationID
                }

                products.append(item)

    # Escribimos los datos en el archivo JSON
    with open(productsJSONFile, 'w') as jsonFile:
        json.dump(products, jsonFile)


# Obtenemos las variaciones desde el archivo CSV y las transformamos a JSON
def ConvertVariations():
    with open(variationsCSVFile, encoding=CSVEncoding) as csvFile:
        csvReader = csv.DictReader(csvFile)
        count = 0
        total_rows = sum(1 for row in csvReader) # Contamos el total de filas en el archivo CSV
        csvFile.seek(0) # Volvemos al inicio del archivo CSV para comenzar a leerlo desde el principio
        next(csvReader) # Saltamos la primera fila

        lastVariationID = None
        for row in csvReader:
            if row['VariacionID']: # Solo procesamos las filas que tengan valor en la primera columna
                # Imprimimos la barra de carga
                count += 1
                progress = (count / total_rows) * 100
                print(f'Procesando fila {count} de {total_rows}... {progress:.2f}%')

                # Creamos un nuevo objeto para cada fila ("option") y lo añadimos al objeto de variation correspondiente ("item")

                if int(row['VariacionID']) != lastVariationID:
                    lastVariationID = int(row['VariacionID'])
                    variation = {
                        'id': int(row['VariacionID']),
                        'title': row['Titulo'],
                        'options': [],
                    }
                    variations.append(variation)

                price = (float(row['PrecioBulto']) if row['PrecioBulto'] != "" else None)
                m2Price = (float(row['PrecioM2']) if row['PrecioM2'] != "" else None)
                description = (row['Descripcion'] if row['Descripcion'] != "" else None)
                image = (row['Imagen'] if row['Imagen'] != "" else None)
                thumbnail  = (row['Miniatura'] if row['Miniatura'] != "" else None)
                units = (int(row['Unidades']) if row['Unidades'] != "" else None)
                m2ByUnit = (float(row['m2porBulto']) if row['m2porBulto'] != "" else None)

                option = {
                    'optionID': int(row['OpcionID']),
                    'value': row['Valor'],
                    'price': price,
                    'm2Price': m2Price,
                    'description': description,
                    'image': image,
                    'thumbnail': thumbnail,
                    'units': units,
                    'm2ByUnit': m2ByUnit
                }

                variations[int(row['VariacionID']) - 1]["options"].append(option)


    # Escribimos los datos en el archivo JSON
    with open(variationsJSONFile, 'w') as jsonFile:
        json.dump(variations, jsonFile)

print("Convirtiendo productos...")
ConvertProducts()
print("Convirtiendo variaciones...")
ConvertVariations()
print("Se han convertido todos los archivos correctamente.")
input("Presione <ENTER> para finzalizar.")
