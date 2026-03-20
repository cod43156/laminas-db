<?php

namespace LaminasTest\Db\Adapter\Platform;

use Laminas\Db\Adapter\Platform\Sql92;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'getName')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'getQuoteIdentifierSymbol')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'quoteIdentifier')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'quoteIdentifierChain')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'getQuoteValueSymbol')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'quoteValue')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'quoteTrustedValue')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'quoteValueList')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'getIdentifierSeparator')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\Laminas\Db\Adapter\Platform\Sql92::class, 'quoteIdentifierInFragment')]
class Sql92Test extends TestCase
{
    /** @var Sql92 */
    protected $platform;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->platform = new Sql92();
    }

    public function testGetName()
    {
        self::assertEquals('SQL92', $this->platform->getName());
    }

    public function testGetQuoteIdentifierSymbol()
    {
        self::assertEquals('"', $this->platform->getQuoteIdentifierSymbol());
    }

    public function testQuoteIdentifier()
    {
        self::assertEquals('"identifier"', $this->platform->quoteIdentifier('identifier'));
    }

    public function testQuoteIdentifierChain()
    {
        self::assertEquals('"identifier"', $this->platform->quoteIdentifierChain('identifier'));
        self::assertEquals('"identifier"', $this->platform->quoteIdentifierChain(['identifier']));
        self::assertEquals('"schema"."identifier"', $this->platform->quoteIdentifierChain(['schema', 'identifier']));
    }

    public function testGetQuoteValueSymbol()
    {
        self::assertEquals("'", $this->platform->getQuoteValueSymbol());
    }

    public function testQuoteValueRaisesNoticeWithoutPlatformSupport()
    {
        $this->expectNotice();
        $this->expectExceptionMessage(
            'Attempting to quote a value without specific driver level support can introduce security vulnerabilities '
            . 'in a production environment.'
        );
        $this->platform->quoteValue('value');
    }

    public function testQuoteValue()
    {
        self::assertEquals("'value'", @$this->platform->quoteValue('value'));
        self::assertEquals("'Foo O\\'Bar'", @$this->platform->quoteValue("Foo O'Bar"));
        self::assertEquals(
            '\'\\\'; DELETE FROM some_table; -- \'',
            @$this->platform->quoteValue('\'; DELETE FROM some_table; -- ')
        );
        self::assertEquals(
            "'\\\\\\'; DELETE FROM some_table; -- '",
            @$this->platform->quoteValue('\\\'; DELETE FROM some_table; -- ')
        );
    }

    public function testQuoteTrustedValue()
    {
        self::assertEquals("'value'", $this->platform->quoteTrustedValue('value'));
        self::assertEquals("'Foo O\\'Bar'", $this->platform->quoteTrustedValue("Foo O'Bar"));
        self::assertEquals(
            '\'\\\'; DELETE FROM some_table; -- \'',
            $this->platform->quoteTrustedValue('\'; DELETE FROM some_table; -- ')
        );

        //                   '\\\'; DELETE FROM some_table; -- '  <- actual below
        self::assertEquals(
            "'\\\\\\'; DELETE FROM some_table; -- '",
            $this->platform->quoteTrustedValue('\\\'; DELETE FROM some_table; -- ')
        );
    }

    public function testQuoteValueList()
    {
        $this->expectError();
        $this->expectExceptionMessage(
            'Attempting to quote a value without specific driver level support can introduce security vulnerabilities '
            . 'in a production environment.'
        );
        self::assertEquals("'Foo O\\'Bar'", $this->platform->quoteValueList("Foo O'Bar"));
    }

    public function testGetIdentifierSeparator()
    {
        self::assertEquals('.', $this->platform->getIdentifierSeparator());
    }

    public function testQuoteIdentifierInFragment()
    {
        self::assertEquals('"foo"."bar"', $this->platform->quoteIdentifierInFragment('foo.bar'));
        self::assertEquals('"foo" as "bar"', $this->platform->quoteIdentifierInFragment('foo as bar'));

        // single char words
        self::assertEquals(
            '("foo"."bar" = "boo"."baz")',
            $this->platform->quoteIdentifierInFragment('(foo.bar = boo.baz)', ['(', ')', '='])
        );

        // case insensitive safe words
        self::assertEquals(
            '("foo"."bar" = "boo"."baz") AND ("foo"."baz" = "boo"."baz")',
            $this->platform->quoteIdentifierInFragment(
                '(foo.bar = boo.baz) AND (foo.baz = boo.baz)',
                ['(', ')', '=', 'and']
            )
        );

        // case insensitive safe words in field
        self::assertEquals(
            '("foo"."bar" = "boo".baz) AND ("foo".baz = "boo".baz)',
            $this->platform->quoteIdentifierInFragment(
                '(foo.bar = boo.baz) AND (foo.baz = boo.baz)',
                ['(', ')', '=', 'and', 'bAz']
            )
        );
    }
}
