<?php
namespace App\Views;

use App\Models\Genero;

class GeneroView {
  public static function renderTablaGeneros($generos) {
    $html = '<table>';
    $html .= '<thead><tr><th>ID</th><th>Nombre</th></tr></thead>';
    $html .= '<tbody>';

    foreach ($generos as $genero) {
    $html .= '<tr>';
    $html .= '<td>' . $genero->getId() . '</td>';
    $html .= '<td>' . $genero->getNombre() . '</td>';
    $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
  }
}