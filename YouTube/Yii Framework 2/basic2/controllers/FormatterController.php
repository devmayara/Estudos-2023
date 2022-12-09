<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class FormatterController extends Controller
{
    public function actionIndex()
    {
        $appLang = Yii::$app->language;
        $formatter = Yii::$app->formatter;

        echo "<h2>{$appLang}</h2>";

        echo "<p>NText: {$formatter->asNtext("Mayara\nGomes\nda\nSilva\n\n")}</p>";

        echo "<p>CEP: {$formatter->asCep(12345678901)}</p>";
        echo "<p>CPF: {$formatter->asCpf(12345678901)}</p>";
        echo "<p>CNPJ: {$formatter->asCnpj(12345678901234)}</p>";
        echo "<p>Size (Tamanhos): {$formatter->asShortSize(12345)}</p>";
        echo "<p>Moedas: {$formatter->asCurrency(2.60, 25)}</p>";
        echo "<p>Data Formato: {$formatter->asDate("1995-10-09", 'dd/MM/yyyy')}</p>";
        echo "<p>Data Formato PHP: {$formatter->asDate("1995-10-09", 'php:d/m/y')}</p>";
        echo "<p>Data: {$formatter->asDate("1995-10-09", 'short')}</p>";
        echo "<p>Data: {$formatter->asDate("1995-10-09", 'medium')}</p>";
        echo "<p>Data: {$formatter->asDate("1995-10-09", 'long')}</p>";
        echo "<p>Data: {$formatter->asDate("1995-10-09", 'full')}</p>";
        echo "<p>E-mails: {$formatter->asEmail('mayara@id5.com')}</p>";
        echo "<p>Booleans: {$formatter->asBoolean(false)}</p>";
        echo "<p>Percentuais: {$formatter->asPercent(0.12345, 2)}</p>";
    }
}