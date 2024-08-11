<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ConfigurationsService
{
    public function getExchangeRatesOfCurrency($from_currency, $to_curreny = null)
    {
        $filePath = base_path('configurations.php');
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;
            $currencies = $config['currencies'];
            $exchange_rates = $config["exchange_rates"];

            if ($to_curreny != null) {
                return $exchange_rates[$from_currency][$to_curreny];
            }
            return $exchange_rates[$from_currency];
        }
    }

    public function updateExchangeRateAmount($from_currency, $to_curreny, $amount)
    {
        $filePath = base_path('configurations.php');
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;
            $exchange_rates = $config["exchange_rates"];
            $exchange_rates[$from_currency][$to_curreny] = $amount;
            $config['exchange_rates'] = $exchange_rates;
            $this->writeConfiguration($config);
            return "amount is updated";
        }
    }
    public function createExchangeRate()
    {
        $filePath = base_path('configurations.php');
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;
            $currencies = $config['currencies'];
            $exchange_rates = $config["exchange_rates"];
            foreach ($currencies as $baseCurrency) {
                if (!isset($exchange_rates[$baseCurrency])) {
                    $exchange_rates[$baseCurrency] = [];
                }
                foreach ($currencies as $targetCurrency) {
                    if ($baseCurrency != $targetCurrency) {
                        if (!isset($exchange_rates[$baseCurrency][$targetCurrency])) {
                            $exchange_rates[$baseCurrency][$targetCurrency] = 0;
                        }
                    }
                }
            }
            $config['exchange_rates'] = $exchange_rates;
            $this->writeConfiguration($config);
            return "exchange rate added";
        }
    }
    public function removeExchangeRateOfCurrency($currency)
    {
        $filePath = base_path('configurations.php');
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;
            $currencies = $config['currencies'];
            $exchange_rates = $config["exchange_rates"];

            if (isset($exchange_rates[$currency])) {
                unset($exchange_rates[$currency]);

                foreach ($exchange_rates as $key => &$subArray) {
                    if (isset($subArray[$currency])) {
                        unset($subArray[$currency]);
                    }
                }
            }
            $config["exchange_rates"] = $exchange_rates;
            $this->writeConfiguration($config);

            return 'Currency exchange rate removed successfully.';
        }
    }
    public function addExchangeRate($currency, $exchange_rate) {}
    public function addCurrencies(array $currencies)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            // Add new currencies to the existing ones
            $config['currencies'] = array_unique(array_merge($config['currencies'], $currencies));

            // Write the updated configuration back to the file
            $this->writeConfiguration($config);

            return 'Currencies added successfully.';
        }

        return 'Configuration file does not exist.';
    }

    public function removeCurrencies(array $currencies)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            // Remove specified currencies
            $config['currencies'] = array_diff($config['currencies'], $currencies);

            // Write the updated configuration back to the file
            $this->writeConfiguration($config);

            return 'Currencies removed successfully.';
        }

        return 'Configuration file does not exist.';
    }

    public function addCurrency($currency)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            // Add new currencies to the existing ones
            array_push($config['currencies'], $currency);
            $config['currencies'] = array_unique($config['currencies']);

            // Write the updated configuration back to the file
            $this->writeConfiguration($config);

            return 'Currency added successfully.';
        }

        return 'Configuration file does not exist.';
    }
    public function removeCurrency($currency)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            // Remove specified currencies
            $config['currencies'] = array_diff($config['currencies'], [$currency]);

            // Write the updated configuration back to the file
            $this->writeConfiguration($config);

            return 'Currency removed successfully.';
        }

        return 'Configuration file does not exist.';
    }
    public function getCurrencies()
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            return $config['currencies'];
        }

        return 'Configuration file does not exist.';
    }

    public function getExchangeRates()
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            return $config['exchange_rates'];
        }

        return 'Configuration file does not exist.';
    }
    public function updateExchangeRates($exchange_rates)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            $config['exchange_rates'] = $exchange_rates;
            $this->writeConfiguration($config);
        }

        return 'Configuration file does not exist.';
    }


    public function updateCurrency($old_currency, $new_currency)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            $currencies = $config['currencies'];
            foreach ($currencies as $key => $currency) {
                if ($currency == $old_currency) {
                    $currencies[$key] = $new_currency;
                }
            }
            $config["currencies"] = $currencies;
            // Write the updated configuration back to the file
            $this->writeConfiguration($config);

            return 'Currency updated successfully.';
        }
    }

    public function updateCurrencyNameExchangeRate($old_currency, $new_currency)
    {
        $filePath = base_path('configurations.php');

        // Check if the file exists
        if (File::exists($filePath)) {
            // Get the current configuration
            $config = include $filePath;

            $exchange_rates = $config['exchange_rates'];
            foreach ($exchange_rates as $key => &$rates) {
                // Check if the key itself is 'JOR' and update it
                if ($key == $old_currency) {
                    $exchange_rates[$new_currency] = $rates;
                    unset($exchange_rates[$old_currency]);
                }
                // Update the nested $old_currency keys inside each currency's rates array
                if (isset($rates[$old_currency])) {
                    $rates[$new_currency] = $rates[$old_currency];
                    unset($rates[$old_currency]);
                }
            }
            $config["exchange_rates"] = $exchange_rates;
            // Write the updated configuration back to the file
            $this->writeConfiguration($config);

            return 'Currency exchange rates updated successfully.';
        }
    }
    private function writeConfiguration(array $config)
    {
        $filePath = base_path('configurations.php');

        // Create the content with updated configuration
        $content = '<?php return ' . var_export($config, true) . ';';

        // Write the content to the file
        File::put($filePath, $content);
    }
}
