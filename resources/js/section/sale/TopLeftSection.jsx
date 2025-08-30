import { useState } from "react";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import MultiSelect from "../../components/MultiSelect";
import SelectSearch from "../../components/SelectSearch";
import { usePage } from "@inertiajs/react";

const TopLeftSection = () => {
    const [selectedDate, setSelectedDate] = useState(null);
    const [invoice, setInvoice] = useState("");
    const [selectedAffiliators, setSelectedAffiliators] = useState([]);
    const { props } = usePage();
    const { affiliators, setting } = props;

    const affiliatorOptions = affiliators.map((affiliator) => ({
        label: affiliator.name,
        value: affiliator.id,
    }));

    return (
        <div className="border border-gray-300 dark:border-gray-600 col-span-1 lg:col-span-4 p-6 bg-background-light dark:bg-background-dark rounded-lg shadow-sm transition-colors duration-300">
            <div className="grid sm:grid-cols-3 gap-4">
                {/* Date Input */}
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                        Date
                    </label>
                    <DatePicker
                        selected={selectedDate}
                        onChange={(date) => setSelectedDate(date)}
                        maxDate={new Date()}
                        dateFormat="dd/MM/yyyy"
                        placeholderText="Select a date"
                        className="w-full py-1 px-3 border border-gray-300 dark:border-gray-600 text-sm rounded-md bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-1 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark transition-colors duration-200"
                        wrapperClassName="w-full"
                    />
                </div>
                {/* Generate Invoice Input */}
                <div>
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1.5">
                        Generate Invoice
                    </label>
                    <input
                        type="text"
                        value={invoice}
                        onChange={(e) => setInvoice(e.target.value)}
                        placeholder="Enter invoice number"
                        className="w-full py-2 px-3 border border-gray-300 dark:border-gray-600 text-sm rounded-md bg-surface-light dark:bg-surface-dark text-text dark:text-text-dark focus:ring-1 focus:ring-primary dark:focus:ring-primary-dark focus:border-primary dark:focus:border-primary-dark transition-colors duration-200"
                    />
                </div>
                {/* Affiliators Multiple Select */}
                <div>
                    <MultiSelect
                        label="Affiliators"
                        options={affiliatorOptions}
                        selectedValues={selectedAffiliators}
                        onChange={setSelectedAffiliators}
                        className="w-full text-sm text-text dark:text-text-dark bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                </div>
            </div>
        </div>
    );
};

export default TopLeftSection;
