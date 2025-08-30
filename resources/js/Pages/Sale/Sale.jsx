import BillingSection from "../../section/sale/BillingSection";
import SaleSettingSection from "../../section/sale/SaleSettingSection";
import SaleTableSection from "../../section/sale/SaleTableSection";
import TopLeftSection from "../../section/sale/TopLeftSection";
import TopRightCustomerSection from "../../section/sale/TopRightCustomerSection";
import ThemeToggle from "../../components/ThemeToggle";
import { Toaster } from "react-hot-toast";

const Sale = () => {
    return (
        <div className="min-h-screen bg-background-light dark:bg-background-dark px-6 md:px-12 py-12 md:py-16 transition-colors duration-300">
            <Toaster position="top-center" reverseOrder={false} />
            <div className="flex items-center justify-between mb-6">
                <a
                    href="/"
                    className="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark dark:bg-primary-dark dark:hover:bg-primary rounded-md transition-colors duration-200 shadow-sm"
                >
                    Back to Dashboard
                </a>
                <ThemeToggle />
            </div>
            <h2 className="text-2xl font-semibold text-text dark:text-text-dark mb-8 rounded-sm border-l-4 border-primary pl-4">
                Sale Page
            </h2>
            <div className="grid grid-cols-1 lg:grid-cols-6 gap-6">
                <div className="lg:col-span-3">
                    <TopLeftSection />
                </div>
                <div className="lg:col-span-3">
                    <TopRightCustomerSection />
                </div>
            </div>
            <div className="mt-8 mb-20">
                <SaleTableSection />
            </div>
            <div className="hidden">
                <SaleSettingSection />
            </div>
            <div className="mt-20">
                <BillingSection />
            </div>
        </div>
    );
};

export default Sale;
