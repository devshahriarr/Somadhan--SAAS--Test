import { useState, useEffect } from "react";
import SelectSearch from "../../components/SelectSearch";
import { Icon } from "@iconify/react";

const SaleTableSection = () => {
    // Sample product data (replace with API data in your project)
    const products = [
        { id: 1, name: "Laptop", price: 50000, sku: "LP001" },
        { id: 2, name: "Smartphone", price: 25000, sku: "SP001" },
        { id: 3, name: "Headphones", price: 5000, sku: "HP001" },
    ];

    const productOptions = products.map((product) => ({
        value: product.id,
        label: `${product.name} - ${product.sku}`,
        product,
    }));

    const [rows, setRows] = useState([
        {
            id: Date.now(),
            sl: 1,
            product: null,
            price: "",
            qty: "",
            discount: "",
            warranty: "",
            total: 0,
        },
    ]);

    // Update total when price, qty, or discount changes
    useEffect(() => {
        setRows((prevRows) =>
            prevRows.map((row) => ({
                ...row,
                total:
                    (parseFloat(row.price) || 0) * (parseInt(row.qty) || 0) -
                    (parseFloat(row.discount) || 0),
            }))
        );
    }, [rows]);

    const handleAddRow = () => {
        setRows([
            ...rows,
            {
                id: Date.now(),
                sl: rows.length + 1,
                product: null,
                price: "",
                qty: "",
                discount: "",
                warranty: "",
                total: 0,
            },
        ]);
    };

    const handleDeleteRow = (id) => {
        if (rows.length > 1) {
            setRows((prevRows) =>
                prevRows
                    .filter((row) => row.id !== id)
                    .map((row, index) => ({
                        ...row,
                        sl: index + 1, // Update SL numbers
                    }))
            );
        }
    };

    const handleInputChange = (id, field, value) => {
        setRows((prevRows) =>
            prevRows.map((row) =>
                row.id === id
                    ? {
                          ...row,
                          [field]: value,
                      }
                    : row
            )
        );
    };

    const handleProductSelect = (id, option) => {
        setRows((prevRows) =>
            prevRows.map((row) =>
                row.id === id
                    ? {
                          ...row,
                          product: option?.product || null,
                          price: option ? option.product.price.toString() : "",
                      }
                    : row
            )
        );
    };

    return (
        <div className="border border-gray-300 dark:border-gray-600 p-6 bg-surface-light dark:bg-surface-dark rounded-lg shadow-sm col-span-6">
            <div className="flex justify-between items-center mb-3">
                <h2 className="text-lg font-semibold text-text dark:text-text-dark mb-4 border-l-4 border-primary pl-3">
                    Sale Items
                </h2>
                <div>
                    <button className="inline-flex items-center px-4 py-2 bg-primary dark:bg-primary-dark text-white rounded-md hover:bg-primary-dark dark:hover:bg-primary focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:ring-opacity-50 transition-colors duration-200 text-sm font-medium shadow-sm">
                        Via Sale
                    </button>
                </div>
            </div>
            <div className="overflow-x-auto">
                <table className="w-full table-auto border-collapse">
                    <thead>
                        <tr className="bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark">
                            <th className="px-4 py-2 text-left text-sm font-medium w-[5%]">
                                SL
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[25%]">
                                Product
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[15%]">
                                Price
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[10%]">
                                Qty
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[15%]">
                                Discount
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[15%]">
                                Warranty
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[15%]">
                                Total
                            </th>
                            <th className="px-4 py-2 text-left text-sm font-medium w-[10%]">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {rows.map((row) => (
                            <tr
                                key={row.id}
                                className="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200"
                            >
                                <td className="px-4 py-2">
                                    <input
                                        type="text"
                                        value={row.sl}
                                        readOnly
                                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                                        aria-label="Serial Number"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <SelectSearch
                                        options={productOptions}
                                        onSelect={(option) =>
                                            handleProductSelect(row.id, option)
                                        }
                                        placeholder="Select a product..."
                                        selectedValue={
                                            row.product
                                                ? {
                                                      value: row.product.id,
                                                      label: `${row.product.name} - ${row.product.sku}`,
                                                  }
                                                : null
                                        }
                                        className="w-full text-sm bg-surface-light dark:bg-surface-dark"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <input
                                        type="number"
                                        value={row.price}
                                        onChange={(e) =>
                                            handleInputChange(
                                                row.id,
                                                "price",
                                                e.target.value
                                            )
                                        }
                                        readOnly={!!row.product}
                                        className={`w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark ${
                                            row.product
                                                ? "cursor-not-allowed"
                                                : ""
                                        }`}
                                        placeholder="Enter price"
                                        aria-label="Price"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <input
                                        type="number"
                                        value={row.qty}
                                        onChange={(e) =>
                                            handleInputChange(
                                                row.id,
                                                "qty",
                                                e.target.value
                                            )
                                        }
                                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark"
                                        placeholder="Enter Qty"
                                        min="0"
                                        aria-label="Quantity"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <input
                                        type="number"
                                        value={row.discount}
                                        onChange={(e) =>
                                            handleInputChange(
                                                row.id,
                                                "discount",
                                                e.target.value
                                            )
                                        }
                                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark"
                                        placeholder="Enter discount"
                                        min="0"
                                        aria-label="Discount"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <input
                                        type="text"
                                        value={row.warranty}
                                        onChange={(e) =>
                                            handleInputChange(
                                                row.id,
                                                "warranty",
                                                e.target.value
                                            )
                                        }
                                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark"
                                        placeholder="Enter warranty"
                                        aria-label="Warranty"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <input
                                        type="text"
                                        value={row.total.toFixed(2)}
                                        readOnly
                                        className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-text dark:text-text-dark focus:ring-0 cursor-not-allowed"
                                        aria-label="Total"
                                    />
                                </td>
                                <td className="px-4 py-2">
                                    <button
                                        onClick={() => handleDeleteRow(row.id)}
                                        disabled={rows.length === 1}
                                        className={`p-2 rounded-md transition-colors duration-200 ${
                                            rows.length === 1
                                                ? "text-gray-400 dark:text-gray-600 cursor-not-allowed"
                                                : "text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-500"
                                        }`}
                                        aria-label="Delete row"
                                    >
                                        <Icon
                                            icon="iconamoon:trash-light"
                                            width="24"
                                            height="24"
                                        />
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            <div className="mt-4 flex justify-end">
                <button
                    onClick={handleAddRow}
                    className="inline-flex items-center px-4 py-2 bg-primary dark:bg-primary-dark text-white rounded-md hover:bg-primary-dark dark:hover:bg-primary focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:ring-opacity-50 transition-colors duration-200 text-sm font-medium shadow-sm"
                    aria-label="Add more rows"
                >
                    <Icon icon="mdi:plus" className="w-5 h-5 mr-2" />
                    Add More
                </button>
            </div>
        </div>
    );
};

export default SaleTableSection;
