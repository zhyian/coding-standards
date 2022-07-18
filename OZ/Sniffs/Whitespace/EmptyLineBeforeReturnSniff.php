<?php
/**
 * Check empty line before returns in a file.
 */

namespace OZ\Sniffs\Whitespace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Sniffs\Sniff;

class EmptyLineBeforeReturnSniff implements Sniff
{
    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array
     */
    public function register(): array
    {
        return [
            T_RETURN
        ];
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * The stackPtr variable indicates where in the stack the token was found.
     * A sniff can acquire information this token, along with all the other
     * tokens within the stack by first acquiring the token stack:
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The PHP_CodeSniffer file where the
     *                                               token was found.
     * @param int                         $stackPtr  The position in the PHP_CodeSniffer
     *                                               file's token stack where the token
     *                                               was found.
     */
    public function process( File $phpcsFile, $stackPtr )
    {
        $tokens = $phpcsFile->getTokens();
        $previous = $phpcsFile->findPrevious( Tokens::$emptyTokens, $stackPtr - 1, null, true );

        if ( $tokens[ $stackPtr ][ 'line' ] - $tokens[ $previous ][ 'line' ] < 2 ) {
            $is_fixed = $phpcsFile->addFixableError(
                sprintf(
                    'Add empty line before return statement in %d line.',
                    $tokens[ $stackPtr ][ 'line' ] - 1
                ),
                $stackPtr,
                'AddEmptyLineBeforeReturnStatement'
            );

            if ( $is_fixed === true ) {
                $phpcsFile->fixer->addNewline( $previous );
            }
        }
    }
}
