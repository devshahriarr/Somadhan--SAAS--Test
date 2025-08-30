import { Icon } from "@iconify/react";
import { useEffect, useRef, useState } from "react";

const SelectSearch = ({
    label,
    options,
    onSelect,
    placeholder,
    buttonText,
    onButtonClick,
    className,
    selectedValue,
}) => {
    const [searchTerm, setSearchTerm] = useState("");
    const [isOpen, setIsOpen] = useState(false);
    const [highlightedIndex, setHighlightedIndex] = useState(-1);
    const wrapperRef = useRef(null);
    const searchInputRef = useRef(null);
    const optionRefs = useRef([]);

    // Focus search input when dropdown opens
    useEffect(() => {
        if (isOpen && searchInputRef.current) {
            searchInputRef.current.focus();
        }
    }, [isOpen]);

    // Sync searchTerm with selectedValue
    useEffect(() => {
        if (selectedValue) {
            setSearchTerm(""); // Clear search term when an option is selected
            setHighlightedIndex(-1); // Reset highlighted index
        }
    }, [selectedValue]);

    const filteredOptions = options.filter((option) =>
        option?.label.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const handleSelect = (option) => {
        setSearchTerm("");
        setIsOpen(false);
        setHighlightedIndex(-1);
        onSelect(option);
    };

    // Handle keyboard navigation
    const handleKeyDown = (e) => {
        if (!isOpen) return;

        switch (e.key) {
            case "ArrowDown":
                e.preventDefault();
                setHighlightedIndex((prev) =>
                    prev < filteredOptions.length - 1 ? prev + 1 : prev
                );
                break;
            case "ArrowUp":
                e.preventDefault();
                setHighlightedIndex((prev) => (prev > 0 ? prev - 1 : prev));
                break;
            case "Enter":
                e.preventDefault();
                if (
                    highlightedIndex >= 0 &&
                    highlightedIndex < filteredOptions.length
                ) {
                    handleSelect(filteredOptions[highlightedIndex]);
                }
                break;
            case "Escape":
                e.preventDefault();
                setIsOpen(false);
                setSearchTerm("");
                setHighlightedIndex(-1);
                break;
            default:
                break;
        }
    };

    // Scroll highlighted option into view
    useEffect(() => {
        if (highlightedIndex >= 0 && optionRefs.current[highlightedIndex]) {
            optionRefs.current[highlightedIndex].scrollIntoView({
                block: "nearest",
                behavior: "smooth",
            });
        }
    }, [highlightedIndex]);

    // Handle click outside to close dropdown
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                wrapperRef.current &&
                !wrapperRef.current.contains(event.target)
            ) {
                setIsOpen(false);
                setSearchTerm(""); // Clear search term when closing dropdown
                setHighlightedIndex(-1); // Reset highlighted index
            }
        };

        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    return (
        <div className="relative" ref={wrapperRef} onKeyDown={handleKeyDown}>
            <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                {label}
            </label>
            <div className="flex items-center">
                <div
                    className={`relative w-full py-0 px-3 border border-gray-300 dark:border-gray-600 rounded-l-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark cursor-pointer transition-colors duration-200 ${className} ${
                        buttonText ? "rounded-r-none" : "rounded-r-md"
                    }`}
                    onClick={() => setIsOpen(true)}
                    role="combobox"
                    aria-expanded={isOpen}
                    aria-controls="search-options"
                >
                    <input
                        type="text"
                        value={selectedValue ? selectedValue.label : ""}
                        readOnly
                        placeholder={placeholder}
                        className="w-full bg-transparent outline-none text-text dark:text-text-dark border-none text-sm focus:ring-0"
                        aria-readonly="true"
                    />
                    <Icon
                        icon="mdi:chevron-down"
                        className="absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 text-text dark:text-text-dark"
                    />
                </div>
                {buttonText && (
                    <button
                        onClick={onButtonClick}
                        className="py-2 px-3 bg-primary dark:bg-primary-dark text-white rounded-r-md hover:bg-primary-dark dark:hover:bg-primary focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark focus:ring-opacity-50 transition-colors duration-200 text-sm font-medium"
                    >
                        {buttonText}
                    </button>
                )}
            </div>
            {isOpen && (
                <div
                    className="absolute z-10 w-full mt-1 bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto"
                    id="search-options"
                    role="listbox"
                >
                    <div className="p-2 sticky top-0 bg-surface-light dark:bg-surface-dark">
                        <input
                            ref={searchInputRef}
                            type="text"
                            value={searchTerm}
                            onChange={(e) => {
                                setSearchTerm(e.target.value);
                                setHighlightedIndex(-1); // Reset highlight when typing
                            }}
                            placeholder="Search..."
                            className="w-full py-1.5 px-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-0 focus:border-primary dark:focus:border-primary-dark transition-colors duration-200"
                            aria-label="Search options"
                        />
                    </div>
                    {filteredOptions.length > 0 ? (
                        <ul role="listbox">
                            {filteredOptions.map((option, index) => (
                                <li
                                    key={option.value}
                                    ref={(el) =>
                                        (optionRefs.current[index] = el)
                                    }
                                    onClick={() => handleSelect(option)}
                                    className={`py-2 px-3 text-sm cursor-pointer transition-colors duration-200 ${
                                        index === highlightedIndex
                                            ? "bg-primary text-white dark:bg-primary-dark dark:text-text-dark"
                                            : selectedValue &&
                                              selectedValue.value ===
                                                  option.value
                                            ? "bg-primary/50 text-text dark:bg-primary-dark/50 dark:text-text-dark"
                                            : "text-text dark:text-text-dark hover:bg-primary hover:text-white dark:hover:bg-primary-dark dark:hover:text-text-dark"
                                    }`}
                                    role="option"
                                    aria-selected={index === highlightedIndex}
                                >
                                    {option?.label ?? "N/A"}
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <div className="py-2 px-3 text-sm text-text dark:text-text-dark">
                            No results found
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default SelectSearch;
