import pandas as pd
import json

excelFilePath = "Base de Datos.xlsx"
productsJSONFile = 'Productos.json'
variationsJSONFile = "Variaciones.json"

products = []
variations = []

# Obtenemos los productos desde el archivo Excel y lo transformamos a JSON
def ConvertProducts():
    df = pd.read_excel(excelFilePath, sheet_name='Productos', dtype={'CODIGO': 'Int64'})
    df = df.dropna(subset=['CODIGO']) # Eliminamos las filas donde la columna CODIGO es nula

    count = 0
    total_rows = df.shape[0] # Obtenemos el número total de filas del dataframe
    for index, row in df.iterrows():
        # Imprimimos la barra de carga
        count += 1
        progress = (count / total_rows) * 100
        print(f'Procesando fila {count} de {total_rows}... {progress:.2f}%')

        # Creamos un nuevo objeto para cada fila y lo añadimos a la lista
        m2Price = (float(row['PrecioM2']) if pd.notnull(row['PrecioM2']) else None)
        description = (row['Descripcion'] if pd.notnull(row['Descripcion']) else None)
        image = (row['Imagen'] if pd.notnull(row['Imagen']) else None)
        thumbnail  = (row['Miniatura'] if pd.notnull(row['Miniatura']) else None)
        category  = (row['Categoria'] if pd.notnull(row['Categoria']) else None)
        units = (int(row['Unidades']) if pd.notnull(row['Unidades']) else None)
        showUnits = (True if row['MostrarUnidades'].upper() == "SI" else False)
        m2ByUnit = (float(row['m2porBulto']) if pd.notnull(row['m2porBulto']) else None)
        variationID = (int(row['VariacionID']) if pd.notnull(row['VariacionID']) else None)

        item = {
            'id': int(row['CODIGO']),
            'name': row['Nombre'],
            'price': row['PrecioBulto'],
            'm2Price': m2Price,
            'description': description,
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
    df = pd.read_excel(excelFilePath, sheet_name='Variaciones', dtype={'VariacionID': 'Int64'})
    df = df.dropna(subset=['VariacionID']) # Eliminamos las filas donde la columna CODIGO es nula

    count = 0
    total_rows = df.shape[0] # Obtenemos el número total de filas del dataframe

    lastVariationID = None
    for index, row in df.iterrows():
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

        price = (float(row['PrecioBulto']) if pd.notnull(row['PrecioBulto']) else None)
        m2Price = (float(row['PrecioM2']) if pd.notnull(row['PrecioM2']) else None)
        description = (row['Descripcion'] if pd.notnull(row['Descripcion']) else None)
        image = (row['Imagen'] if pd.notnull(row['Imagen']) else None)
        thumbnail  = (row['Miniatura'] if pd.notnull(row['Miniatura']) else None)
        units = (int(row['Unidades']) if pd.notnull(row['Unidades']) else None)
        m2ByUnit = (float(row['m2porBulto']) if pd.notnull(row['m2porBulto']) else None)

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
