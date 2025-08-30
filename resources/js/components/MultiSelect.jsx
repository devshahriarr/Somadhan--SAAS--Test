import React, { useState, useRef } from "react";
import { Icon } from "@iconify/react";
import useOutsideClick from "../hook/useOutsideClick";

const MultiSelect = ({ options, selectedValues, onChange, label }) => {
    const [searchTerm, setSearchTerm] = useState("");
    const [isOpen, setIsOpen] = useState(false);
    const dropdownRef = useRef(null);

    useOutsideClick(dropdownRef, () => setIsOpen(false));

    const filteredOptions = options.filter((option) =>
        option.label.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const handleSelect = (option) => {
        const isSelected = selectedValues.some(
            (val) => val.value === option.value
        );
        if (isSelected) {
            onChange(
                selectedValues.filter((val) => val.value !== option.value)
            );
        } else {
            onChange([...selectedValues, option]);
        }
        setSearchTerm("");
    };

    const handleAddAffiliator = () => {
        setIsOpen(!isOpen);
    };

    const handleRemoveTag = (option) => {
        onChange(selectedValues.filter((val) => val.value !== option.value));
    };

    return (
        <div className="relative" ref={dropdownRef}>
            <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                {label}
            </label>
            <div
                className="relative flex items-center border border-gray-300 dark:border-gray-600 rounded-md bg-surface-light dark:bg-surface-dark focus-within:ring-2 focus-within:ring-primary dark:focus-within:ring-primary-dark focus-within:border-primary dark:focus-within:border-primary-dark cursor-pointer transition-colors duration-200"
                onClick={handleAddAffiliator}
            >
                <div className="flex flex-wrap items-center gap-1.5 w-full p-1">
                    {selectedValues.map((option) => (
                        <div
                            key={option.value}
                            className="flex items-center py-1 px-2 bg-primary text-white dark:bg-primary-dark dark:text-text-dark rounded-md text-sm"
                        >
                            {option?.label ?? "N/A"}
                            <button
                                onClick={(e) => {
                                    e.stopPropagation();
                                    handleRemoveTag(option);
                                }}
                                className="ml-1 text-white hover:text-gray-200 dark:hover:text-gray-400"
                            >
                                <Icon icon="mdi:close" className="w-4 h-4" />
                            </button>
                        </div>
                    ))}
                    {selectedValues.length === 0 && (
                        <span className="text-sm text-muted dark:text-muted-dark py-1 px-2">
                            Search...
                        </span>
                    )}
                </div>
            </div>
            {isOpen && (
                <div className="absolute z-10 w-full mt-1 bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <input
                        type="text"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        onFocus={() => setIsOpen(true)}
                        placeholder="Search..."
                        className="w-full py-2 px-3 text-sm border-b border-gray-300 dark:border-gray-600 bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:outline-none focus:ring-0 transition-colors duration-200"
                    />
                    {filteredOptions.length > 0 ? (
                        filteredOptions.map((option) => (
                            <div
                                key={option.value}
                                onClick={() => handleSelect(option)}
                                className={`p-2 cursor-pointer text-sm hover:bg-primary hover:text-white dark:hover:bg-primary-dark dark:hover:text-text-dark ${
                                    selectedValues.some(
                                        (val) => val.value === option.value
                                    )
                                        ? " text-primary  dark:text-text-dark"
                                        : "text-text dark:text-text-dark"
                                } transition-colors duration-200`}
                            >
                                {option?.label ?? "N/A"}
                            </div>
                        ))
                    ) : (
                        <div className="p-2 text-sm text-muted dark:text-muted-dark">
                            No options found
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default MultiSelect;
