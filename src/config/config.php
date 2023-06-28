<?php
// Archivo de configuración global

// Configuración para ocultar las advertencias "Notice" y los "Warning"
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
  // error_reporting() -> establece qué niveles de error se muestran
  // E_ALL -> todos los errores
  // ~E_WARNING -> todos los errores excepto E_WARNING
  // ~E_NOTICE -> todos los errores excepto E_NOTICE
  // Esto para evitar que se modifique el mensjae devuleto de error
  