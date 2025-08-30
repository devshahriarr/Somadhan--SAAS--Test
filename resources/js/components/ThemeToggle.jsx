import { Icon } from "@iconify/react";
import { useState } from "react";

const ThemeToggle = () => {
    const [isDarkMode, setIsDarkMode] = useState(false);

    const toggleDarkMode = () => {
        setIsDarkMode(!isDarkMode);
        document.documentElement.classList.toggle("dark");
    };
    return (
        <div className="absolute top-4 right-4">
            <button
                onClick={toggleDarkMode}
                className="p-2 bg-primary text-white dark:bg-primary-dark dark:text-text-dark rounded-full hover:bg-primary-dark dark:hover:bg-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-colors duration-200 shadow-sm"
                aria-label={
                    isDarkMode ? "Switch to Light Mode" : "Switch to Dark Mode"
                }
            >
                {isDarkMode ? (
                    <Icon icon="solar:moon-broken" className="w-6 h-6" />
                ) : (
                    <Icon icon="solar:sun-broken" className="w-6 h-6" />
                )}
            </button>
        </div>
    );
};

export default ThemeToggle;
