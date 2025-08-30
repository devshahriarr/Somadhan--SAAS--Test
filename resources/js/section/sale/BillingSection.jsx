import { useState, useEffect } from "react";
import SelectSearch from "../../components/SelectSearch";
import { Icon } from "@iconify/react";

const BillingSection = ({ rows = [] }) => {
    // Sample payment methods (replace with API data in your project)
    const paymentMethods = [
        { value: "cash", label: "Cash" },
        { value: "card", label: "Card" },
        { value: "mobile_banking", label: "Mobile Banking" },
    ];

    const [discountType, setDiscountType] = useState("%");
    const [discount, setDiscount] = useState("");
    const [tax, setTax] = useState("");
    const [previousDue, setPreviousDue] = useState("");
    const [payAmount, setPayAmount] = useState("");
    const [paymentMethod, setPaymentMethod] = useState(null);
    const [productTotal, setProductTotal] = useState(0);
    const [subTotal, setSubTotal] = useState(0);
    const [advanceDue, setAdvanceDue] = useState(0);

    // Calculate product total from rows
    useEffect(() => {
        const total = rows.reduce(
            (sum, row) => sum + (parseFloat(row.total) || 0),
            0
        );
        setProductTotal(total);
    }, [rows]);

    // Calculate sub total and advance/due
    useEffect(() => {
        const discountValue =
            discountType === "%"
                ? ((parseFloat(discount) || 0) * productTotal) / 100
                : parseFloat(discount) || 0;
        const taxValue = ((parseFloat(tax) || 0) * productTotal) / 100;
        const calculatedSubTotal = productTotal - discountValue + taxValue;
        setSubTotal(calculatedSubTotal);

        const calculatedAdvanceDue =
            calculatedSubTotal - (parseFloat(payAmount) || 0);
        setAdvanceDue(calculatedAdvanceDue);
    }, [productTotal, discount, discountType, tax, payAmount]);

    const handleDiscountChange = (e) => {
        const value = e.target.value;
        if (discountType === "%" && value) {
            if (value < 1 || value > 100) return; // Restrict to 1-100 for percentage
        }
        setDiscount(value);
    };

    const handlePayClick = () => {
        // Handle Pay button logic (e.g., API call)
        console.log("Pay button clicked", {
            rows,
            discount,
            tax,
            previousDue,
            payAmount,
            paymentMethod,
        });
    };

    const handleDraftClick = () => {
        // Handle Draft button logic (e.g., save as draft)
        console.log("Draft button clicked", {
            rows,
            discount,
            tax,
            previousDue,
            payAmount,
            paymentMethod,
        });
    };

    return (
        <div className="fixed bottom-0 left-0 w-full bg-surface-light dark:bg-surface-dark border-t border-gray-300 dark:border-gray-600 shadow-md z-10">
            <div className="mx-auto px-6 md:px-12 py-4 md:py-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Product Total
                    </label>
                    <input
                        type="text"
                        value={productTotal.toFixed(2)}
                        readOnly
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                        aria-label="Product Total"
                    />
                </div>
                <div className="flex items-end gap-2">
                    <div className="flex-1">
                        <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                            Discount
                        </label>
                        <input
                            type="number"
                            value={discount}
                            onChange={handleDiscountChange}
                            min={discountType === "%" ? 1 : 0}
                            max={discountType === "%" ? 100 : undefined}
                            className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark rounded-r-none"
                            placeholder="Enter discount"
                            aria-label="Discount"
                        />
                    </div>
                    <select
                        value={discountType}
                        onChange={(e) => setDiscountType(e.target.value)}
                        className="w-16 py-1.5 px-2 border border-gray-300 dark:border-gray-600 border-l-0 rounded-md rounded-l-none text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark appearance-none"
                        aria-label="Discount Type"
                    >
                        <option value="%">%</option>
                        <option value="৳">৳</option>
                    </select>
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Tax (%)
                    </label>
                    <input
                        type="number"
                        value={tax}
                        onChange={(e) => setTax(e.target.value)}
                        min="0"
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark"
                        placeholder="Enter tax %"
                        aria-label="Tax Percentage"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Sub Total
                    </label>
                    <input
                        type="text"
                        value={subTotal.toFixed(2)}
                        readOnly
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                        aria-label="Sub Total"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Previous Due
                    </label>
                    <input
                        type="number"
                        value={previousDue}
                        onChange={(e) => setPreviousDue(e.target.value)}
                        min="0"
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                        placeholder="Enter previous due"
                        aria-label="Previous Due"
                        readOnly
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Grand Total
                    </label>
                    <input
                        type="number"
                        value={previousDue}
                        onChange={(e) => setPreviousDue(e.target.value)}
                        min="0"
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                        placeholder="Enter previous due"
                        aria-label="Previous Due"
                        readOnly
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Pay Amount
                    </label>
                    <input
                        type="number"
                        value={payAmount}
                        onChange={(e) => setPayAmount(e.target.value)}
                        min="0"
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark"
                        placeholder="Enter pay amount"
                        aria-label="Pay Amount"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Advance/Due Amount
                    </label>
                    <input
                        type="text"
                        value={advanceDue.toFixed(2)}
                        readOnly
                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                        aria-label="Advance or Due Amount"
                    />
                </div>
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Payment Method
                    </label>
                    <SelectSearch
                        options={paymentMethods}
                        onSelect={(option) => setPaymentMethod(option)}
                        placeholder="Select payment method..."
                        selectedValue={paymentMethod}
                        className="w-full text-sm bg-surface-light dark:bg-surface-dark"
                        aria-label="Payment Method"
                    />
                </div>
                <div className="sm:col-span-2 lg:col-span-4 flex justify-end gap-4 mt-4">
                    <button
                        onClick={handleDraftClick}
                        className="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 text-white rounded-md hover:bg-gray-600 dark:hover:bg-gray-500 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-600 focus:ring-opacity-50 transition-colors duration-200 text-sm font-medium shadow-sm"
                        aria-label="Save as Draft"
                    >
                        <Icon
                            icon="mdi:content-save"
                            className="w-5 h-5 mr-2"
                        />
                        Draft
                    </button>
                    <button
                        onClick={handlePayClick}
                        className="inline-flex items-center px-4 py-2 bg-primary dark:bg-primary-dark text-white rounded-md hover:bg-primary-dark dark:hover:bg-primary focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:ring-opacity-50 transition-colors duration-200 text-sm font-medium shadow-sm"
                        aria-label="Pay"
                    >
                        <Icon icon="mdi:credit-card" className="w-5 h-5 mr-2" />
                        Pay
                    </button>
                </div>
            </div>
        </div>
    );
};

export default BillingSection;
