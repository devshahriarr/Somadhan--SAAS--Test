import { useState } from "react";
import SelectSearch from "../../components/SelectSearch";
import { usePage } from "@inertiajs/react";
import CustomerModal from "../../components/CustomerModal";
import axios from "axios";
import toast from "react-hot-toast";

const TopRightCustomerSection = () => {
    const { props } = usePage();
    const { customers: initialCustomers } = props;

    const [customers, setCustomers] = useState(initialCustomers);
    const [selectedCustomer, setSelectedCustomer] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);

    // Transform customers data to { value, label } structure
    const customerOptions = customers.map((customer) => ({
        value: customer.id,
        label: `${customer.name} - ${customer.phone || "N/A"}`,
        customer: customer,
    }));

    // Fetch updated customer list
    const fetchCustomers = async () => {
        try {
            const response = await axios.get("/get/customer");
            // console.log("get customer", response);
            if (response.data.status === 200) {
                setCustomers(response?.data?.allData);
            } else {
                console.error(response?.data?.message);
            }
        } catch (error) {
            console.error("Error fetching customers:", error);
        }
    };

    const handleCustomerSelect = (option) => {
        setSelectedCustomer(option?.customer);
    };

    const handleButtonClick = () => {
        setIsModalOpen(true);
    };

    const handleModalClose = () => {
        setIsModalOpen(false);
    };

    const handleModalSubmit = async (formData) => {
        try {
            const response = await axios.post("/add/customer", {
                name: formData.name,
                phone: formData.phone,
                email: formData.email,
                address: formData.address,
                opening_receivable: formData.openingBalance,
                opening_payable: formData.previousDue,
            });

            console.log("add customer", response);
            if (response?.data?.status === 200) {
                // Fetch updated customer list
                await fetchCustomers();
                // Select the newly added customer
                const newCustomer = response.data.customer;
                setSelectedCustomer(newCustomer);
                // Close the modal
                setIsModalOpen(false);
                toast.success(
                    response?.data?.message ?? "Customer Add Successful"
                );
            } else {
                alert(response.data.error || "Failed to add customer.");
            }
        } catch (error) {
            if (error.response?.status === 400) {
                alert(
                    Object.values(error.response.data.error).flat().join("\n")
                );
            } else {
                alert("An unexpected error occurred. Please try again.");
            }
        }
    };

    return (
        <div className="border border-gray-300 dark:border-gray-600 col-span-1 lg:col-span-2 p-6 bg-background-light dark:bg-background-dark rounded-lg shadow-sm transition-colors duration-300">
            <div className="grid sm:grid-cols-2 gap-6">
                <SelectSearch
                    label="Customer"
                    options={customerOptions}
                    onSelect={handleCustomerSelect}
                    placeholder="Search for a customer..."
                    buttonText="Add"
                    onButtonClick={handleButtonClick}
                    selectedValue={
                        selectedCustomer
                            ? {
                                  value: selectedCustomer.id,
                                  label: `${selectedCustomer.name} - ${
                                      selectedCustomer.phone || "N/A"
                                  }`,
                              }
                            : null
                    }
                    className="w-full text-sm text-text dark:text-text-dark bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-md"
                />

                <div>
                    {/* <h3 className="text-base font-semibold text-text dark:text-text-dark border-l-4 border-primary pl-3">
                        Customer Information
                    </h3> */}
                    <label className="block text-sm font-medium text-text dark:text-text-dark mb-1">
                        Customer Information
                    </label>
                    <div className=" grid sm:grid-cols-2  gap-0.5">
                        <p className="text-sm text-text dark:text-text-dark">
                            <span className="font-medium text-muted dark:text-muted-dark">
                                Name:
                            </span>{" "}
                            {selectedCustomer?.name ?? "N/A"}
                        </p>
                        <p className="text-sm text-text dark:text-text-dark">
                            <span className="font-medium text-muted dark:text-muted-dark">
                                Phone:
                            </span>{" "}
                            {selectedCustomer?.phone ?? "N/A"}
                        </p>
                        <p className="text-sm text-text dark:text-text-dark">
                            <span className="font-medium text-muted dark:text-muted-dark">
                                Due Amount:
                            </span>{" "}
                            ৳ {selectedCustomer?.wallet_balance ?? "N/A"}
                        </p>
                    </div>
                </div>
            </div>
            <CustomerModal
                isOpen={isModalOpen}
                onClose={handleModalClose}
                onSubmit={handleModalSubmit}
            />
        </div>
    );
};

export default TopRightCustomerSection;
