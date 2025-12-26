/**
 * Currency Formatter for RWF (Rwandan Franc)
 */
export const CurrencyFormatter = {
    CURRENCY_CODE: 'RWF',
    CURRENCY_SYMBOL: 'FRw',
    DECIMAL_PLACES: 0,

    /**
     * Format amount to RWF currency string
     */
    format: (amount, includeSymbol = true) => {
        if (amount === null || amount === undefined) {
            return includeSymbol ? 'FRw 0' : '0';
        }

        const formatted = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(parseFloat(amount));

        if (includeSymbol) {
            return `FRw ${formatted}`;
        }

        return formatted;
    },

    /**
     * Format amount to display format (short form)
     */
    formatShort: (amount) => {
        const num = parseFloat(amount) || 0;

        if (num >= 1000000) {
            return `FRw ${(num / 1000000).toFixed(1)}M`;
        } else if (num >= 1000) {
            return `FRw ${(num / 1000).toFixed(1)}K`;
        }

        return `FRw ${new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(num)}`;
    },

    /**
     * Get currency symbol
     */
    getSymbol: () => 'FRw',

    /**
     * Get currency code
     */
    getCode: () => 'RWF',

    /**
     * Parse currency string to number
     */
    parse: (value) => {
        if (typeof value === 'number') {
            return value;
        }

        // Remove currency symbol and formatting
        let cleaned = String(value);
        cleaned = cleaned.replace('FRw', '');
        cleaned = cleaned.replace('FRw', '');
        cleaned = cleaned.replace(/,/g, '');
        cleaned = cleaned.trim();

        return parseFloat(cleaned) || 0;
    }
};

export default CurrencyFormatter;
