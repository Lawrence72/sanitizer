<?php

declare(strict_types=1);

use Lawrence72\Sanitizer\Sanitizer;
use PHPUnit\Framework\TestCase;

final class StringTest extends TestCase {
	protected Sanitizer $sanitizer;

	public function setUp(): void {
		$this->sanitizer = new Sanitizer();
	}
	public function testPlainText() {
		$text = "hello world";
		$this->assertSame("hello world", $this->sanitizer->clean($text));
	}

	public function testSpecialCharacters() {
		$text = "hello world!@#$%^&*()_+";
		$this->assertSame("hello world!@#$%^&amp;*()_+", $this->sanitizer->clean($text));
	}

	public function testGreaterThanLessThan() {
		$text = "hello world< >";
		$this->assertSame("hello world&lt; &gt;", $this->sanitizer->clean($text));
	}

	public function testHTMLTags() {
		$text = "<b>hello world</b>";
		$this->assertSame("hello world", $this->sanitizer->clean($text));
	}

	public function testHTMLTagsAllowed() {
		$text = "<b>hello world</b>";
		$this->assertSame("<b>hello world</b>", $this->sanitizer->clean($text, ['b']));
	}

	public function testHTMLTagsAllowedWithSpecialCharacters() {
		$text = "<b>hello world!@#$%^&*()_+</b>";
		$this->assertSame("<b>hello world!@#$%^&amp;*()_+</b>", $this->sanitizer->clean($text, ['b']));
	}

	public function testEncoding() {
		$text = "Привет! Hello! ¡Hola! 你好! مرحبا! Bonjour! 안녕하세요! Ciao! Olá!";
		$this->assertSame("&#1055;&#1088;&#1080;&#1074;&#1077;&#1090;! Hello! &iexcl;Hola! &#20320;&#22909;! &#1605;&#1585;&#1581;&#1576;&#1575;! Bonjour! &#50504;&#45397;&#54616;&#49464;&#50836;! Ciao! Ol&aacute;!", $this->sanitizer->clean($text));
	}

	public function testEncodingWithTags() {
		$text = "<b>Привет! Hello! ¡Hola! 你好! مرحبا! Bonjour! 안녕하세요! Ciao! Olá!</b>";
		$this->assertSame("<b>&#1055;&#1088;&#1080;&#1074;&#1077;&#1090;! Hello! &iexcl;Hola! &#20320;&#22909;! &#1605;&#1585;&#1581;&#1576;&#1575;! Bonjour! &#50504;&#45397;&#54616;&#49464;&#50836;! Ciao! Ol&aacute;!</b>", $this->sanitizer->clean($text, ['b']));
	}
}
