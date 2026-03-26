<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Dinámicos</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; padding: 20px; color: #343a40; }
        .contenedor { max-width: 900px; margin: auto; }
        .seccion { background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; border-top: 4px solid #007bff; }
        h1 { text-align: center; color: #007bff; margin-bottom: 30px; }
        h2 { color: #495057; border-bottom: 2px solid #e9ecef; padding-bottom: 10px; }
        
        textarea, input[type="text"] { width: 100%; padding: 10px; margin-top: 8px; margin-bottom: 15px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: center; }
        th { background-color: #f1f3f5; color: #495057; }
        
        .aprobado { color: #28a745; font-weight: bold; }
        .reprobado { color: #dc3545; font-weight: bold; }
        .mejor-promedio { background-color: #d4edda; } 
        .peor-promedio { background-color: #f8d7da; }  
        .resaltado { background-color: #fff3cd; font-weight: bold; padding: 0 4px; border-radius: 3px; }
    </style>
</head>
<body>

<div class="contenedor">
    <h1>Sistema de Reportes Académicos</h1>

    <div class="seccion">
        <h2>Parte A - Procesador de Nombres</h2>
        <form method="POST">
            <label for="nombres_input">Ingrese nombres separados por comas:</label>
            <textarea name="nombres_input" id="nombres_input" rows="3" placeholder="ej: juan pérez, MARÍA gómez, carlos RuIz"></textarea>
            <button type="submit" name="procesar_nombres">Procesar Nombres</button>
        </form>

        <?php
        if (isset($_POST['procesar_nombres']) && !empty($_POST['nombres_input'])) {
            $nombres_raw = $_POST['nombres_input'];
            
            $arreglo_nombres = explode(',', $nombres_raw);
            $nombres_procesados = [];

            foreach ($arreglo_nombres as $nombre) {
                // Eliminar espacios sobrantes
                $nombre_limpio = trim($nombre);
                
                if (!empty($nombre_limpio)) {
                    // Capitalizar correctamente maneja bien los acentos
                    $nombre_capitalizado = mb_convert_case(mb_strtolower($nombre_limpio), MB_CASE_TITLE, "UTF-8");
                    $nombres_procesados[] = $nombre_capitalizado;
                }
            }

            echo "<h3>Nombres Procesados:</h3>";
            echo "<ol>";
            foreach ($nombres_procesados as $np) {
                echo "<li>" . htmlspecialchars($np) . "</li>";
            }
            echo "</ol>";
            
            echo "<p><strong>Total de nombres procesados:</strong> " . count($nombres_procesados) . "</p>";
        }
        ?>
    </div>

    <div class="seccion">
        <h2>Parte B - Tabla de Calificaciones</h2>
        <?php
        // Declarar arreglo de estudiantes y sus notas
        $estudiantes = [
            "Ana López" => [8, 9, 7, 10],
            "Carlos Ruiz" => [5, 4, 6, 5],
            "Elena Sofía" => [10, 10, 9, 10],
            "Luis Méndez" => [6, 7, 6, 6],
            "Pedro Paco" => [3, 2, 4, 5]
        ];

        // Calcular promedios primero para encontrar el mayor y menor
        $promedios = [];
        foreach ($estudiantes as $nombre => $notas) {
            $promedio = array_sum($notas) / count($notas);
            $promedios[$nombre] = $promedio;
        }

        $mejor_nota = max($promedios);
        $peor_nota = min($promedios);
        ?>

        <table>
            <tr>
                <th>Estudiante</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Nota 3</th>
                <th>Nota 4</th>
                <th>Promedio</th>
                <th>Estado</th>
            </tr>
            <?php
            foreach ($estudiantes as $nombre => $notas) {
                $promedio = $promedios[$nombre];
                
                // Determinar estado de aprobación
                $estado = ($promedio >= 6) ? "<span class='aprobado'>Aprobado</span>" : "<span class='reprobado'>Reprobado</span>";
                
                // Determinar si es el mejor o peor promedio para la clase CSS
                $clase_fila = "";
                if ($promedio == $mejor_nota) {
                    $clase_fila = "mejor-promedio";
                } elseif ($promedio == $peor_nota) {
                    $clase_fila = "peor-promedio";
                }

                echo "<tr class='$clase_fila'>";
                echo "<td>" . htmlspecialchars($nombre) . "</td>";
                echo "<td>" . $notas[0] . "</td>";
                echo "<td>" . $notas[1] . "</td>";
                echo "<td>" . $notas[2] . "</td>";
                echo "<td>" . $notas[3] . "</td>";
                echo "<td><strong>" . number_format($promedio, 2) . "</strong></td>";
                echo "<td>" . $estado . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="seccion">
        <h2>Parte C - Buscador de Palabras en Texto</h2>
        <form method="POST">
            <label for="texto_base">Texto principal:</label>
            <textarea name="texto_base" id="texto_base" rows="4" required>PHP es un lenguaje de código abierto muy popular especialmente adecuado para el desarrollo web y que puede ser incrustado en HTML. Aprender php es fundamental para el backend.</textarea>
            
            <label for="palabra_clave">Palabra a buscar:</label>
            <input type="text" name="palabra_clave" id="palabra_clave" required>
            
            <button type="submit" name="buscar_palabra">Buscar</button>
        </form>

        <?php
        if (isset($_POST['buscar_palabra'])) {
            $texto = $_POST['texto_base'];
            $palabra = $_POST['palabra_clave'];

            if (!empty($texto) && !empty($palabra)) {
                $texto_seguro = htmlspecialchars($texto);
                $palabra_segura = htmlspecialchars($palabra);
                $coincidencias = substr_count(strtolower($texto_seguro), strtolower($palabra_segura));

                $patron = '/' . preg_quote($palabra_segura, '/') . '/i';
                $reemplazo = '<span class="resaltado">$0</span>';
                $texto_resaltado = preg_replace($patron, $reemplazo, $texto_seguro);

                echo "<h3>Resultados de la búsqueda:</h3>";
                echo "<p style='line-height: 1.6; border: 1px solid #ddd; padding: 15px; border-radius: 4px;'>" . $texto_resaltado . "</p>";
                echo "<p><strong>Coincidencias encontradas:</strong> " . $coincidencias . "</p>";
            }
        }
        ?>
    </div>

</div>

</body>
</html>