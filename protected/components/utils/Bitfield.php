<?php

/**
 * This class provides a simple way to manage bitfields
 *
 *<code>
 *	$bits = new Bitfield("01110100");
 *	echo $bits->get(0); # 0
 *	echo $bits->get(1); # 1
 *	echo $bits->toggle(2); # 0
 *	echo $bits->toString(); # 01010100
 *	echo $bits->toString(8); # 124
 *</code> 
 */
class Bitfield implements IteratorAggregate, Serializable {

	/**
	 * to store the bits
	 *
	 * @var int
	 */
	private $_bits = 0;

	/**
	 */
	static function factory($mask) {
		return new self($mask);
	}

	/**
	* Creates a new Bitfield instance
	* Additionaly you can set the bits in tow formats: int or bin-string
	*
	*<code>
	*	$bit = new Bitfield(0xf0c5); # set as int
	*	echo $bit;
	*
	*	$bit = new Bitfield('1110110101001010'); # set as bin-string
	*	echo $bit;
	*
	*	$bit = new Bitfield('7523', 8); # using a different base
	*	echo $bit;
	*</code>
	*
	* @param int|string $mask
	* @param int $base
	*/	
	function __construct (/*int|string*/$mask=false, /*int*/ $base=2) {
		
		if (is_string($mask)) {
			$this->fromBase($mask, $base);
		}
		elseif (is_int($mask)) {
			$this->_bits = $mask;
		} elseif (false !== $mask) {
			throw new Exception('invalid $mask type, given ' . gettype($mask));
		}
	}

	/**
	* @return string
	*/
	function __toString (/*void*/) {
		return $this->toBin();
	}
	
	/**
	* @return int
	*/
	function valueOf () {
		return $this->_bits;
	}
	
	#-------------------------------------------------------------------------#
	# Methods required by interfaces 
	
	/**
	* @return ArrayIterator
	*/
	function getIterator (/*void*/) {
        return new ArrayIterator($this->toArray());
    }
	
	/**
	* @return string
	*/
	function serialize (/*void*/) {
		return strval($this->_bits);
	}
	
	/**
	* @param string $serialized
	* @return Bitfield
	*/
	function unserialize (/*string*/$serialized) {
		return $this->fromBin($serialized);
	}
	
	#-------------------------------------------------------------------------#
	# Bit Operations
	
	/**
	* Get the value of the bit at the $offset
	*
	* @param int $offset
	* @return boolean
	*/
	function get (/*int*/$offset) {
		$mask = 1 << $offset;
		return ($mask & $this->_bits) == $mask;
	}
	
	/**
	* Set the bit at $offset to true
	*
	* @param int $offset
	* @return Bitfield
	*/
	function set (/*int*/$offset) {
		$this->_bits |= 1 << $offset;
		return $this;
	}
	
	/**
	* Reset the bit at $offset.
	*
	* @param int $offset
	* @return Bitfield
	*/
	function reset (/*int*/$offset) {
		$this->_bits &= ~ (1 << $offset);
		return $this;
	}
	
	/**
	* Toggle the bit at $offset. 
	* If the bit is set then reset it, and viceversa.
	*
	* @param int $offset
	* @return Bitfield
	*/
	function toggle (/*int*/$offset) {
		$this->_bits ^= 1 << $offset;
		return $this->get($offset);
	}
	
	/**
	 * @param int $word_size
	 */
	function reverse ($word_size=8) {
		$size = (strlen($this->toBin()) % $word_size + 1) * $word_size;
		return $this->fromString(strrev($this->toString(2, $size)));
	}

	#-------------------------------------------------------------------------#
	# Conversion - Inputs
	function fromArray($arr) {
		return implode($arr);
	}
	/**
	 * @deprecated 
	 * 
	 * @param int $number
	 * @return Bitfield
	 */
	function fromNumber (/*int*/$number){
		$this->_bits = (int) $number;
		return $this;
	}

	/**
	 * @deprecated 
	 *
	 * @param string $string
	 * @return Bitfield
	 */
	function fromString (/*string*/$string){
		$this->_bits = bindec($string);
		return $this;
	}
	
	/**
	* Set the bits from a $number.
	* The $number must be in $base
	*
	*<code>
	*	$b = new Bitfield();
	*	$b->fromBase('753', 8); # set to 111 101 011
	*	echo $b->toOct();
	*</code>
	*
	* @param string $number
	* @param int $base
	* @return Bitfield
	*/
	function fromBase (/*string*/$number,/*int*/$base) {
		$this->_bits = base_convert($number, $base, 10);
		return $this;
	}
	
	/**
	* Set the bits from a string with an $hex number ([0-9a-fA-F]+)
	*
	* @see Bitfield::fromBase
	*
	* @param int $number
	* @param int $base
	* @return Bitfield
	*/
	function fromHex (/*string*/$hex) {
		$this->_bits = $this->fromBase($hex, 16);
		return $this;
	}
	
	/**
	* Set the bits from a string with an $oct number ([0-7]+)
	*
	* @see Bitfield::fromBase
	*
	* @param int $number
	* @return Bitfield
	*/
	function fromOct (/*string*/$oct)
	{
		$this->_bits = $this->fromBase($oct, 8);
		return $this;
	}
	
	/**
	* Set the bits from a string with an $bin number ([01]+)
	*
	* @see Bitfield::fromBase
	*
	* @param int $bin
	* @return Bitfield
	*/
	function fromBin (/*string*/$bin)
	{
		$this->_bits = $this->fromBase($bin, 2);
		return $this;
	}
	
	#-------------------------------------------------------------------------#
	# Conversion - Outputs
	
	/**
	* Return the bits in a numeric representation
	*
	*<code>
	*	$b = new Bitfield('10010110'); # set to 150
	*	echo $b->toNumber();
	*</code>
	*
	* @return int
	*/
	function toNumber (/*void*/) {
		return $this->_bits;
	}
	
	/**
	* Return the bits in a string representation
	*
	* If $max_size is given it will pad the bits with 0's
	*
	*<code>
	*	$b = new Bitfield(150); # set to 10010110
	*	echo $b->toString(16);
	*</code>
	*
	* @param int $max_size
	* @param int $max_size
	* @return string
	*/
	function toString (/*int*/$base=2, /*int*/$max_size=false) {
	
		$result = $this->toBase($base);
		
		if (!is_int($max_size)) {
			return $result;
		}
		
		return str_pad($result, $max_size, 0, STR_PAD_LEFT) ;
	}
	
	/**
	* Returns an array of ints, where each position holds true or false
	*
	*<code>
	*	$bits = new Bitfield(0xF0); # set to 1111 0000
	*	$bitsArray = $bits->toArray();
	*
	*	print_r($bitsArray); # => array(true, true, true, true, false, false, false, false);
	*
	*</code>
	*
	* @return array
	*/
	function toArray (/*void*/) {
		return str_split(strrev($this->toBin()));
	}
	
	/**
	* Returns the bits as a string with a numeric representation in $base
	*
	*<code>
	*	$b = new Bitfield();
	*	$b->fromBase('112233', 4);
	*	echo $b->toBase(4);
	*</code>
	*
	* @param int $base
	* @return string 
	*/
	function toBase (/*int*/$base) {
		return base_convert($this->_bits, 10, $base);
	}
	
	/**
	* Returns the bits as a hexdecimal-string number
	*
	* @see Bitfield::toBase
	*
	* @return string
	*/
	function toHex (/*void*/) {
		return $this->toBase(16);
	}
	
	/**
	* Returns the bits as a octal-string number
	*
	* @see Bitfield::toBase
	*
	* @return string
	*/
	function toOct (/*void*/) {
		return $this->toBase(8);
	}
	
	/**
	* Returns the bits as a binary-string number
	*
	* @see Bitfield::toBase
	*
	* @return string
	*/
	function toBin (/*void*/) {
		return $this->toBase(2);
	}
	
	function binBit($int = NULL) {
		return decbin($int);
	}
	
	function binArray($bin) {
		return explode("",$bin);
	}
	
	function toBaseBit($bit,$num) {
		$length = strlen($bit);
		$arr = "";
		if ($length < $num) {
			for ($i=0; $i < $num - $length; $i++) {
				$arr .= "0";
			}
			return $arr.$bit;
		} else {
			return substr($bit, 0, $num);
		}
		
		return $bit;
	}
	
	function toBaseArray($String) {
		
		$arrBit = array();
		for ($i = 0;$i < strlen($String); $i++) {
			$arrBit[] = substr($String, $i, 1);
		}
		
		return $arrBit;
	}
}
