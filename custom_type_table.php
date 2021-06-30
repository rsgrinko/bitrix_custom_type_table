<?php
/*
	Кастомный тип - Таблица
	ООО SeoVen
	Roman S Grinko <rsgrinko@gmail.com>
*/
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', array('rsgrinko', 'GetUserTypeDescription'));
class rsgrinko
{

    function GetUserTypeDescription()
    {
        return array(
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'custom_table',
            'DESCRIPTION' => 'Таблицы',
            'GetPropertyFieldHtml' => array('rsgrinko', 'GetPropertyFieldHtml'),
            'ConvertToDB' => array('rsgrinko', 'ConvertToDB'),
            'ConvertFromDB' => array('rsgrinko', 'ConvertFromDB'),
            'PrepareSettings' => array('rsgrinko', 'PrepareSettings'),
            'GetSettingsHTML' => array('rsgrinko', 'GetSettingsHTML')            
        );
    }
    
    function PrepareSettings($arFields)
    {
        $col_count = intval($arFields['USER_TYPE_SETTINGS']['COL_COUNT']);
        if($col_count <= 1) $col_count = 1;
        $col_title = $arFields['USER_TYPE_SETTINGS']['COL_TITLE'];
       
        return array('COL_COUNT' => $col_count, 'COL_TITLE' => $col_title);
    }
	
	function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        $arPropertyFields = array(
            'HIDE' => array('FILTRABLE', 'COL_TITLE', 'COL_COUNT', 'DEFAULT_VALUE'),
            'SET' => array('FILTRABLE' => 'N'),
            'USER_TYPE_SETTINGS_TITLE' => 'Настройки кастомного свойства'
        );

        return '<tr><td>Количество столбцов таблицы:</td><td><input type="text" size="50" name="'.$strHTMLControlName['NAME'].'[COL_COUNT]" value="'.$arProperty['USER_TYPE_SETTINGS']['COL_COUNT'].'"></td><td></td></tr>
<tr><td>Названия столбцов через разделитель (<b>:||:</b>):</td><td><textarea name="'.$strHTMLControlName['NAME'].'[COL_TITLE]" cols="50" rows="3">'.$arProperty['USER_TYPE_SETTINGS']['COL_TITLE'].'</textarea></td></tr>';
    }
    
    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
	 	
        $value = $value['VALUE'];
        $field = '';
        	$col_title = explode(':||:', $arProperty['USER_TYPE_SETTINGS']['COL_TITLE']);
        	
        	$field .= '<div style="display: flex" class="custom_property_title_'.$arProperty['CODE'].'">';
            for ($i = 0; $i < $arProperty['USER_TYPE_SETTINGS']['COL_COUNT']; $i++) {
                $field .= '<span style="width: 100px;margin-right: 22px;">'.$col_title[$i].'</span>';
            }
            $field .= '</div>';
        
            $field .= '<div style="display: flex">';
            for ($i = 0; $i < $arProperty['USER_TYPE_SETTINGS']['COL_COUNT']; $i++) {
                $field .= '<input style="width: 100px; margin-right: 10px !important;" type="text" name="' . $strHTMLControlName["VALUE"] . '['.$i.']" value="' . $value[$i] . '">';
            }
            $field .= '</div>';
            $field .= '<hr>';
        
			$field .= '<style>.custom_property_title_'.$arProperty['CODE'].'.not_show {display: none !important;}</style>
			<script>
			Array.prototype.forEach.call(document.querySelectorAll(".custom_property_title_'.$arProperty['CODE'].'"), function(e, index){
			if(index==0){
				e.classList.remove(\'not_show\');
			} else {
				e.classList.add(\'not_show\');
			}
			});
			</script>';
        return $field;
    }
    

    function ConvertToDB($arProperty, $value)
    {
	      $return = false;
        if(is_array($value) and array_key_exists('VALUE', $value))
        {
            $arValue = array();
            foreach($value['VALUE'] as $key => $value){
               $arValue[$key] = htmlspecialchars($value);
            }
            $return = array('VALUE' => serialize($arValue),);
        }
        
        return $return;
    }

    function ConvertFromDB($arProperty, $value)
    {
        $return = false;
        if(!is_array($value['VALUE']))
        {
            $return = array('VALUE' => unserialize($value['VALUE']));
        }
        return $return;
    }

}
