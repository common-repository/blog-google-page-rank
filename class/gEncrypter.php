<?php

/**
 * gEncrypter - PHP Class to encrypt and decrypt datas using a given key
 *
 * Tested and working on PHP 4.3.2 and higher
 *
 * LICENSE: This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v2 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @author      Giulio Bai <slide.wow@gmail.com>
 * @copyright   (C)2007 Giulio Bai
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @version     1.0
 * @link        http://hewle.com
 */
 

/**
 * Ecrypts and decrypts text using a user-provided key to create a new alphabet.
 * To get the original text the key is needed again and, if the keys match, 
 * the original text is given back.
 *
 * I haven't understood why, but certain characters (§, °, £, è, é, ò, � , ù, ç)
 * can not be used and converted because it will return a blank space. However
 * I tested all the others chars and letters (both lowercase and uppercase) and
 * I found it works, converting text (and files too!) and then recreating the
 * original given entry. 
 *
 * @author      Giulio Bai <slide.wow@gmail.com>
 * @copyright   (C)2007 Giulio Bai
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @version     1.0
 * @link        http://hewle.com
 */ 
class gEncrypter
{

    /**
     * Key needed by the methods to encrypt and decrypt documents
     *
     * @var string
     */
    var $key;
        
        
    /**
     * Creates a new alphabet depending on the key provided.
     *
     * The process is something like the following:
     * Consider the key $key = "ilarvet"
     *	
     * The program creates an array using as indexes the chars of the key and
     * assigns to them the first letters of the alphabet, without any double char
     *
     * So the conv array will be
     * $conv = array(
     *			"i" => "b",
     *			"l" => "c",
     *			"a" => "d",
     *			"r" => "f",
     *			"v" => "g",
     *			"e" => "h",
     *			"t" => "j"
     *		);
     *
     * Then sobstitutes corresponding letters looking up for them into the conv array
     * a b c d e f g h i j k l m n o p q r s t u v w x y z 
     * d ? ? ? h ? ? ? b ? ? c ? ? ? ? ? f ? j ? g ? ? ? ?
     *
     * Assigns the already-known chars
     * a b c d e f g h i j k l m n o p q r s t u v w x y z 
     * d i l a h r v e b t ? c ? ? ? ? ? f ? j ? g ? ? ? ?
     *
     * Assigns to each letter its opposite in the sequence, avoiding already
     * assigned chars
     * a b c d e f g h i j k l m n o p q r s t u v w x y z
     * d i l a h r v e b t z c y x w u s f q j p g o n m k
     *
     * We now have a new alphabet!
     *
     * @return mixed the array of the new alphabet
     */    
    function gMA()
    {
        $key = $this->key;
        
        $a = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 
                   'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
                   'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                   'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
                   'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', 
                   '9', '0', ',', '.', ';', ':', '-', '_', '!', '$', '%', '&',
                   '/','(', ')', '=', '?', '^', '*', '+', '[', ']', '{', '}',
                   '<', '>', '|', '\\', '\'', '"', ' '
                  );


        // array of key's chars
        $key_array = array();

        // key conversion array
        $conv = array();

        // new alphabet
        $new_a = array();

        // not assigned chars
        $na = array();


        // Creates the key array
        for ($i=0; $i<strlen($key); $i++) {
            $char = substr($key, $i, 1);

            if (!in_array($char, $key_array)) {
                $key_array[$i] = $char;
            } else
                die("Check your key syntax!");
        }

        // converts the letters already known in the new alphabet
        for ($i=0; $i<count($a); $i++)
            $new_a[$a[$i]] = $a[$i];

        // print_r($new_a);

        $count = 0;

        // Back assignement in the new alpabet and filling the conv array
        for ($i=0; $i<strlen($key); $i++) {
            $char = substr($key, $i, 1);
            
            while (in_array($a[$count], $conv) || in_array($a[$count], $key_array))
                $count ++;
            
            $conv[$char] = $a[$count]; 
            $new_a[$char] = $conv[$char];
            
            for ($k=0; $k<count($a); $k++)
                $new_a[$new_a[$a[$k]]] = $a[$k];
        }


        // Fills in the notassigned array
        for ($i=0; $i<count($a); $i++) {
            if ($new_a[$a[$i]] == $a[$i])
                $na[] = $a[$i];    
        }

        $c = count($na);

        /**
         * Alphabet inversion process
         *
         * 1. take a letter
         * 2. has it changed from the original alphabet?
         *    YES -> 3. next letter
         *                ---> 2
         *    NO -> 4. take the opposite letter in the original alphabet
         *                5. is it already assigned to another letter?
         *                    YES -> 6. take the letter immediately before
         *                                ---> 5
         *                    NO -> assign to the first letter taken the free letter
         *                            ---> 3  
         */
        for ($i=0; $i<count($new_a); $i++) {
            if ($new_a[$a[$i]] == $a[$i]) {
                $new_a[$a[$i]] = $na[$c-1];
                
                $c --;
            }
        }

        return $new_a;
    }


    /**
     * Encrypts or decrypts datas depending on a certain key
     *
     * Makes a new alphabet depending on the key provided then replaces each char
     * in the text with its corresponding in the new alphabet.
     *
     * Please NOTE this function encrypts AND decrypts; this mean if the text
     * passed to it is already encrypted using this system and the same key, it
     * will return the original one, else it will return a text encrypted two
     * times using two DIFFERENT keys and you'll need two decryption to get the
     * original text back.
     *
     * If the text passed to the method isn't ecnrypted yet, it will return the
     * text encrypted once. The same thing happens if you use a key which match
     * the sequence of the original alphabet.
     *
     * @param string $string the text to encrypt/decrypts
     * @param string $key the string to use to encrypt/decrypt
     * @return string the string encrypted/decrypted
     */  
    function gED($string, $key)
    {    
        $this->key = $key;

        $new_a = $this->gMA();

        $enc = "";

        // Creating the new text
        for ($i=0; $i<strlen($string); $i++) {
            $cs = substr($string, $i, 1);

            //  if ($cs == " ")
            //      $enc .= " ";
            //   else {    
                $cs = $new_a[$cs];
                $enc .= $cs;
            //   }
            
        }

        //  print_r($new_a);

        return $enc;   
    }

}

?>
