<?php

namespace App\Services;

class ContentModerationService
{
    /**
     * Lista de palabras ofensivas en español que serán censuradas
     */    private static $offensiveWords = [
        // Términos racistas y xenófobos
        'negro', 'negra', 'negros', 'negras', 'sudaca', 'sudacas', 'moro', 'mora', 'moros', 'moras', 
        'gitano', 'gitana', 'gitanos', 'gitanas', 'polacos', 'polaca', 'polacas',
        'chino', 'china', 'chinos', 'chinas', 'indio', 'india', 'indios', 'indias', 
        'boliviano', 'boliviana', 'bolivianos', 'bolivianas', 'colombiano', 'colombiana', 'colombianos', 'colombianas',
        'venezolano', 'venezolana', 'venezolanos', 'venezolanas', 'peruano', 'peruana', 'peruanos', 'peruanas', 
        'ecuatoriano', 'ecuatoriana', 'ecuatorianos', 'ecuatorianas',
        'gringo', 'gringa', 'gringos', 'gringas', 'guiri', 'guiris', 'gachupín', 'gachupin', 'gachupines',
        'blanco', 'blanca', 'blancos', 'blancas',
        
        // Términos homófobos y transfóbicos
        'maricón', 'maricon', 'maricones', 'marica', 'maricas', 'puto', 'puta', 'putos', 'putas', 
        'joto', 'jota', 'jotos', 'jotas', 'mariposón', 'mariposon', 'mariposones',
        'tortillera', 'tortilleras', 'bollera', 'bolleras', 'travesti', 'travestis', 'travelo', 'travelos', 
        'sidoso', 'sidosa', 'sidosos', 'sidosas', 'gay', 'gays', 'lesbiana', 'lesbianas',
        'homosexual', 'homosexuales', 'bisexual', 'bisexuales', 'transexual', 'transexuales', 
        'transgender', 'transgenders', 'afeminado', 'afeminada', 'afeminados', 'afeminadas',
        'machorra', 'machorras', 'machona', 'machonas', 'invertido', 'invertida', 'invertidos', 'invertidas', 
        'degenerado', 'degenerada', 'degenerados', 'degeneradas',
          // Términos sexistas y misóginos
        'zorra', 'zorras', 'perra', 'perras', 'guarra', 'guarras', 'golfa', 'golfas', 'furcia', 'furcias', 
        'cualquiera', 'facilona', 'facilonas', 'facilita', 'facilitas',
        'ramera', 'rameras', 'fulana', 'fulanas', 'pendeja', 'pendejas', 'pendejo', 'pendejos', 
        'cabrona', 'cabronas', 'cabron', 'cabrones', 'cabrón',
        'hijo de puta', 'hija de puta', 'hijos de puta', 'hijas de puta', 'hijo puta', 'hija puta', 'hijaputa', 'hijoputa',
        'machista', 'machistas', 'feminazi', 'feminazis', 'hembrista', 'hembristas',
        
        // Términos clasistas y discriminatorios
        'chusma', 'plebe', 'populacho', 'gentuza', 'escoria', 'basura', 'rata', 'ratas', 'lacra', 'lacras',
        'marginal', 'marginales', 'favelado', 'favelada', 'favelados', 'faveladas', 
        'villero', 'villera', 'villeros', 'villeras', 'choni', 'chonis', 'cani', 'canis',
        'paleto', 'paleta', 'paletos', 'paletas', 'cateto', 'cateta', 'catetos', 'catetas', 
        'ignorante', 'ignorantes',
        
        // Términos por discapacidad
        'retrasado', 'retrasada', 'retrasados', 'retrasadas', 'mongólico', 'mongólica', 'mongólicos', 'mongólicas', 
        'subnormal', 'subnormales', 'deficiente', 'deficientes',
        'tarado', 'tarada', 'tarados', 'taradas', 'idiota', 'idiotas', 'imbécil', 'imbéciles', 
        'tonto', 'tonta', 'tontos', 'tontas', 'estúpido', 'estúpida', 'estúpidos', 'estúpidas',
        'mongolico', 'mongolica', 'mongolicos', 'mongolicas', 'inválido', 'inválida', 'inválidos', 'inválidas', 
        'minusválido', 'minusválida', 'minusválidos', 'minusválidas',
        'loco', 'loca', 'locos', 'locas', 'chiflado', 'chiflada', 'chiflados', 'chifladas', 
        'chalado', 'chalada', 'chalados', 'chaladas',
        
        // Términos por edad
        'vejestorio', 'carcamal', 'fósil', 'reliquia', 'viejo verde', 'vieja verde',
        'anciano', 'anciana', 'abuelo', 'abuela', 'cascajo',
        
        // Términos por apariencia física
        'gordo', 'gorda', 'obeso', 'obesa', 'ballena', 'cerdo', 'cerda', 'cochino', 'cochina',
        'feo', 'fea', 'monstruo', 'bestia', 'aberración', 'esperpento', 'adefesio',
        'enano', 'enana', 'pigmeo', 'pigmea', 'gigante', 'jorobado', 'jorobada',
        'calvo', 'calva', 'pelón', 'pelona', 'cabezón', 'cabezona',
        
        // Insultos generales
        'cabrón', 'cabron', 'cabrona', 'gilipollas', 'gilipolla', 'capullo', 'capulla',
        'memo', 'bobo', 'boba', 'tonto', 'tonta', 'imbécil', 'cretino', 'cretina',
        'animal', 'bestia', 'salvaje', 'bárbaro', 'bárbara', 'incivilizado', 'incivilizada',
        'escoria', 'basura', 'mierda', 'caca', 'porquería', 'asqueroso', 'asquerosa',
        
        // Términos vulgares
        'joder', 'jodan', 'jodete', 'coño', 'cojones', 'hostia', 'hostias',
        'follar', 'folla', 'polvo', 'polvos', 'mamada', 'mamadas', 'chupada', 'chupadas',
        'corrida', 'corridas', 'eyacular', 'eyaculación', 'masturbación', 'masturbar',
        'pajear', 'pajero', 'pajera', 'paja', 'pajas',
        
        // Términos religiosos ofensivos
        'ateo', 'atea', 'hereje', 'blasfemo', 'blasfema', 'sacrílego', 'sacrílega',
        'pagano', 'pagana', 'infiel', 'demonio', 'diablo', 'satanás',
        
        // Términos políticos extremos
        'fascista', 'nazi', 'comunista', 'rojo', 'roja', 'terrorista', 'extremista',
        'radical', 'fanático', 'fanática',
        
        // Variaciones con caracteres especiales o números
        'n3gr0', 'n3gr4', 'm4ric0n', 'm4ric4', 'p3nd3j0', 'p3nd3j4',
        'c4br0n', 'c4br0n4', 'g1l1p0ll4s', 'j0d3r',
        
        // Términos adicionales ofensivos
        'escupitajo', 'basura humana', 'desecho', 'indeseable', 'parásito', 'parásita',
        'sanguijuela', 'aprovechado', 'aprovechada', 'vago', 'vaga', 'holgazán', 'holgazana',
        'sinvergüenza', 'caradura', 'fresco', 'fresca', 'chulo', 'chula'
    ];    /**
     * Censura el contenido reemplazando palabras ofensivas con asteriscos
     *
     * @param string $content
     * @return string
     */
    public static function moderateContent(?string $content): ?string
    {
        if (empty($content)) {
            return $content;
        }

        $moderatedContent = $content;

        foreach (self::$offensiveWords as $word) {
            // Crear un patrón de expresión regular que sea insensible a mayúsculas/minúsculas
            // y que coincida con la palabra completa (no como parte de otra palabra)
            $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
            
            // Reemplazar con asteriscos del mismo tamaño que la palabra
            $replacement = str_repeat('*', strlen($word));
            
            $moderatedContent = preg_replace($pattern, $replacement, $moderatedContent);
        }

        return $moderatedContent;
    }    /**
     * Verifica si el contenido contiene palabras ofensivas
     *
     * @param string $content
     * @return bool
     */
    public static function containsOffensiveContent(?string $content): bool
    {
        if (empty($content)) {
            return false;
        }

        foreach (self::$offensiveWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene las palabras ofensivas encontradas en el contenido
     *
     * @param string $content
     * @return array
     */
    public static function getOffensiveWords(?string $content): array
    {
        if (empty($content)) {
            return [];
        }

        $foundWords = [];

        foreach (self::$offensiveWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
            if (preg_match($pattern, $content)) {
                $foundWords[] = $word;
            }
        }

        return $foundWords;
    }
}
