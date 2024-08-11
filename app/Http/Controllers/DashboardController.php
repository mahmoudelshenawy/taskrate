<?php

namespace App\Http\Controllers;

use App\Models\Amount;
use App\Services\ConfigurationsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    protected $configurationsService;
    public function __construct()
    {
        $this->createConfigurationFile();
        $this->configurationsService = new ConfigurationsService();
    }
    public function index()
    {
        $data["title"] = "Dashboard";
        return view("pages.index", $data);
    }

    public function currenciesPage()
    {
        $data["title"] = "Currencies list";
        $data["currencies"] = $this->configurationsService->getCurrencies();
        return view("pages.currencies", $data);
    }
    public function addNewCurrency(Request $request)
    {
        $request->validate([
            'code' => "required|min:2"
        ]);
        try {
            $this->configurationsService->addCurrency($request->code);
            $this->configurationsService->createExchangeRate();

            return back()->with("success", "currency is added successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }
    public function editCurrency(Request $request)
    {
        $request->validate([
            "old_code" => "required",
            'code' => "required|min:2"
        ]);
        try {
            $this->configurationsService->updateCurrency($request->old_code, $request->code);
            $this->configurationsService->updateCurrencyNameExchangeRate($request->old_code, $request->code);

            return back()->with("success", "currency is updated successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }
    public function deleteCurrency(Request $request)
    {
        $request->validate([
            'code' => "required|min:2"
        ]);
        try {
            $this->configurationsService->removeCurrency($request->code);
            $this->configurationsService->removeExchangeRateOfCurrency($request->code);

            return back()->with("success", "currency is deleted successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }

    public function exchangeRatesPage()
    {
        $data["title"] = "Exchange Rates";
        $data["exchange_rates"] = $this->configurationsService->getExchangeRates();
        return view("pages.exchange_rates", $data);
    }

    public function updateExchangeRate(Request $request)
    {
        try {
            $exchange_rates = $request->exchange_rates;
            $exchange_rates = $this->configurationsService->updateExchangeRates($exchange_rates);
            return back()->with("success", "exchange rates is updated successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }

    public function amountsPage()
    {
        $data["title"] = "Amount List";
        $data["amounts"] = Amount::all();
        $data["currencies"] = $this->configurationsService->getCurrencies();
        $data["exchange_rates"] = $this->configurationsService->getExchangeRates();
        return view("pages.amounts", $data);
    }

    public function addNewAmount(Request $request)
    {
        $request->validate([
            "amount" => "required|min:0|numeric",
            "currency" => "required"
        ]);
        try {
            $amount = Amount::create([
                "amount" => $request->amount,
                "currency" => $request->currency
            ]);
            return back()->with("success", "amount is added successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }

    public function editAmount(Request $request)
    {
        $request->validate([
            "amount_edit" => "required|min:0|numeric",
            "id_edit" => "required|numeric",
            "currency_edit" => "required"
        ]);
        try {

            $amount = Amount::findOrFail($request->id_edit);
            $amount->amount = $request->amount_edit;
            $amount->currency = $request->currency_edit;
            $amount->save();
            return back()->with("success", "amount is updated successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }
    public function deleteAmount(Request $request)
    {
        $request->validate([
            "id_delete" => "required|numeric"
        ]);
        try {
            Amount::destroy($request->id_delete);
            return back()->with("success", "amount is deleted successfully");
        } catch (Exception $ex) {
            return back()->with("error", "something went wrong please try again");
        }
    }
    private function createConfigurationFile()
    {
        $filePath = base_path('configurations.php');

        // Check if the file already exists
        if (!File::exists($filePath)) {
            // Define the content of the file
            $content = <<<'PHP'
<?php

return [
    'currencies' => [],
    'exchange_rates' => [],
];
PHP;

            // Create the file with the content
            File::put($filePath, $content);
        }
    }
}
