<?php

namespace NFePHP\eFinanc\Common;

/**
 * Class FakePretty shows event and fake comunication data for analises and debugging
 *
 * @category  API
 * @package   NFePHP\eFinanc
 * @copyright Copyright (c) 2024
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-efinanceira for the canonical source repository
 */
class FakePrettyRest
{
    public static function prettyPrint($response)
    {
        if (empty($response)) {
            $html = "Sem resposta";
            return $html;
        }
        $std = json_decode($response);
        $msg = !empty($std->message) ? $std->message : '';

        $html = "<pre>";
        $html .= '<h2>Método</h2>';
        $html .= "<p>{$std->method}</p>";
        $html .= "<br>";
        $html .= '<h2>URL</h2>';
        $html .= "<p>{$std->url}</p>";
        $html .= "<br>";
        $html .= '<h2>Operação</h2>';
        $html .= "<p>{$std->operation}</p>";
        $html .= "<br>";
        $html .= '<h2>Mensagem</h2>';
        $html .= "<p>{$msg}</p>";
        $html .= "</pre>";
        return $html;
    }
}
