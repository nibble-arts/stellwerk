<?PHP
//
// STELLWERK
//
// This file is part of the STELLWERK railway control system.
//
// Copyright(c) 2013 Thomas H Winkler
// thomas.winkler@iggmp.net
//
// This file may be licensed under the terms of of the
// GNU General Public License Version 3 (the ``GPL'').
//
// Software distributed under the License is distributed
// on an ``AS IS'' basis, WITHOUT WARRANTY OF ANY KIND, either
// express or implied. See the GPL for the specific language
// governing rights and limitations.
//
// You should have received a copy of the GPL along with this
// program. If not, go to http://www.gnu.org/licenses/gpl.html
// or write to the Free Software Foundation, Inc.,
// 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
//

//------------------------------------------------------------------------------
// insert complex xml structure to xml object
//
// option = ALL ... insert starting at xml basis
// option = ALL_UNIQUE ... insert starting at xml basis if node does not exist
// option = UNIQUE ... insert if node does not exist

function simplexml_insert(&$xml_to,$xml_from,$option = "")
{
	if ($xml_from)
	{
		$option = strtoupper($option);
		$name = $xml_from->getName();

// parse options
		switch ($option)
		{
// insert starting at xml basis if node does not exists
			case 'ALL_UNIQUE':
				if ($xml_to->$name->getName() == $name)
				break;
				
// insert starting at xml basis
			case 'ALL':
				$option = ""; // clear option for recursion

				$new_child = $xml_to->addChild($name);
				foreach($xml_from->attributes() as $attr_key => $attr_value)
				{
					$new_child->addAttribute($attr_key,$attr_value);
				}
				
				// recursion to new entry
				simplexml_insert($new_child,$xml_from);

				break;

// insert if node does not exist
			case 'UNIQUE':
				if ($xml_to->$name->getName() == $name)
				break;

			default:
//------------------------------------------------------------------------------
// insert complex structure
				if (count($xml_from->children()))
				{
					foreach ($xml_from->children() as $xml_child)
					{
						$xml_temp = $xml_to->addChild($xml_child->getName(), (string) $xml_child);
						foreach ($xml_child->attributes() as $attr_key => $attr_value)
						{
						    $xml_temp->addAttribute($attr_key, $attr_value);
						}

		// add recursive
						if (count($xml_child->children()))
							simplexml_insert($xml_temp, $xml_child);
					}
				}
				else
		// insert single entry
				{
					$xml_temp = $xml_to->addChild($xml_from->getName(), (string) $xml_from);
					foreach ($xml_from->attributes() as $attr_key => $attr_value)
					{
							$xml_temp->addAttribute($attr_key, $attr_value);
					}
				}
				break;
		}
	}
}

?>
