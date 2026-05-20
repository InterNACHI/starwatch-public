<?php

namespace App\Support\PhpCsFixer\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use SplFileInfo;

class BlankLineIndentationFixer extends AbstractFixer implements WhitespacesAwareFixerInterface
{
	public function getName(): string
	{
		return 'InterNACHI/blank_line_indentation';
	}
	
	public function getDefinition(): FixerDefinitionInterface
	{
		return new FixerDefinition(
			'Blank lines must be indented to the same level as surrounding code.',
			[
				new CodeSample(
					"<?php\nclass Foo\n{\n\tpublic function bar()\n\t{\n\t\t\$baz = true;\n\n\t\treturn \$baz;\n\t}\n}\n"
				),
			]
		);
	}
	
	public function getPriority(): int
	{
		return -1;
	}
	
	public function isCandidate(Tokens $tokens): bool
	{
		return $tokens->isTokenKindFound(T_WHITESPACE);
	}
	
	protected function applyFix(SplFileInfo $file, Tokens $tokens): void
	{
		$line_ending = $this->whitespacesConfig->getLineEnding();
		
		for ($index = $tokens->count() - 1; $index >= 0; --$index) {
			$token = $tokens[$index];
			
			if (! $token->isGivenKind(T_WHITESPACE)) {
				continue;
			}
			
			$content = $token->getContent();
			
			if (substr_count($content, $line_ending) < 2) {
				continue;
			}
			
			$indent = Utils::calculateTrailingWhitespaceIndent($token);
			$new_content = $this->rebuildWhitespaceWithIndentation($content, $indent, $line_ending);
			
			if ($new_content !== $content) {
				$tokens[$index] = new Token([T_WHITESPACE, $new_content]);
			}
		}
	}
	
	protected function rebuildWhitespaceWithIndentation(string $content, string $indent, string $line_ending): string
	{
		$lines = explode($line_ending, $content);
		$line_count = count($lines);
		
		foreach ($lines as $i => $line) {
			if ($i === 0 || $i === $line_count - 1) {
				continue;
			}
			
			$lines[$i] = $indent;
		}
		
		return implode($line_ending, $lines);
	}
}
