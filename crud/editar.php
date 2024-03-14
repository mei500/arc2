<?php 
include_once "../config/conexion.php";

$id = $_POST['IdE'];
$persona = $_POST['PersonaE'];
$activo = $_POST['ActivoE'];
$ubicacion = $_POST['ubicacionE'];
$fecha = $_POST['fechaE'];

// Es una modificación de una asignación existente
// Obtener los datos originales de la asignación y almacenar el ID antiguo
$sqlOriginal = "SELECT * FROM asignacion_activo WHERE id_asignacion = $id";
$resultadoOriginal = mysqli_query($conectar, $sqlOriginal);

if ($filaOriginal = mysqli_fetch_assoc($resultadoOriginal)) {
    $id_antiguo = $filaOriginal['id_asignacion'];

    // Actualizar los datos en la tabla principal
    $sqlUpdate = "UPDATE asignacion_activo SET as_persona='$persona', as_activo='$activo', Ubicacion='$ubicacion', Fecha='$fecha' WHERE id_asignacion='$id'";
    $resultadoUpdate = mysqli_query($conectar, $sqlUpdate);

    // Verificar si la actualización fue exitosa
    if ($resultadoUpdate) {
        // Insertar los datos modificados en el historial
        $sqlHistorialModificado = "INSERT INTO historial_asignacion_activo (id_asignacion, as_persona_nuevo, as_activo_nuevo, ubicacion_nuevo, fecha_nuevo, id_asignacion_antiguo, as_persona_antiguo, as_activo_antiguo, ubicacion_antiguo, fecha_antiguo) 
                                    VALUES ('$id', '$persona', '$activo', '$ubicacion', '$fecha', '$id_antiguo', '{$filaOriginal['as_persona']}', '{$filaOriginal['as_activo']}', '{$filaOriginal['Ubicacion']}', '{$filaOriginal['Fecha']}')";
        $resultadoHistorialModificado = mysqli_query($conectar, $sqlHistorialModificado);

        // Verificar si las consultas se realizaron con éxito
        if ($resultadoHistorialModificado) {
            header("location:../index.php");
        } else {
            echo "Error al insertar en el historial.";
        }
    } else {
        echo "Error al actualizar los datos.";
    }
} else {
    echo "No se encontró ningún registro con el ID proporcionado.";
}
?>
