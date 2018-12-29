<?php


require_once __DIR__ . '/load.php';


$file = "output_data_filtered_final.xlsx";

// EXCEL READER
$inputFileType = PHPExcel_IOFactory::identify($file);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($file);
$objWorkSheet = $objPHPExcel->getActiveSheet();

$output_files = [
    'agriculture' => 'agriculture.sql',
    'annee' => 'annee.sql',
    'dataset' => 'dataset.sql',
    'energie' => 'energie.sql',
    'gaz' => 'gaz.sql',
    'pays' => 'pays.sql',
    'population' => 'population.sql'
];


$data = [

    'ForestArea_SqrKm' => 0,
    'AgriculturalLand_SqrKm' => 0,
    'ArableLand_PerOfLandArea' => 0,
    'AgriIrrigatedLand' => 0,

    'ElecPowerConsumpt_kWhPerCapita' => 0,
    'RenewEnergyConsumpt' => 0,
    'RenewElecOutput' => 0,
    'ElecProdOilSources' => 0,
    'ElecProdNuclearSources' => 0,
    'ElecProdNaturalGasSources' => 0,
    'ElecProdHydroelectricSources' => 0,
    'ElecProdCoalSources' => 0,
    'AccessToElec_PerOfPop' => 0,

    'CO2Emissions_kt' => 0,
    'CO2EmisLiquidFuelConsumpt_kt' => 0,
    'CO2EmisGaseousFuelConsumpt_kt' => 0,
    'MethaneEmis_ktOfCO2' => 0,
    'TotGreenGasHouseEmis_ktOfCO2' => 0,

    'PopulationTotal' => 0,
    'UrbanPopulation' => 0,

    'Annee' => 0,
    'Pays' => 0

];

/*
parcourt ligne par ligne, le but :
à chaque colonne, récupérer la valeur actuelle, et l'enregistrer dans un tableau
à la fin du parcourt de chaque colonne, (a2)
faire des insert en incluant toutes les infos recueilli dans les tableaux et
en récupérant les ID de chaque insert
puis faire l'insert final, c'est à dire l'insert dataset avec les ID récupéré juste avant

*/


$rows = $objWorkSheet->getRowIterator();

foreach ($rows as $row) {
    if (!isRowEmpty($objWorkSheet, $row)) {

        $rowIndex = $row->getRowIndex();

        if ($rowIndex != 1) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            /**
             * @var $cell PHPExcel_Cell
             */
            foreach ($cellIterator as $cell) {
                $data = processCell($bdd, $objWorkSheet, $cell, $data);
            }
            // Ici, on effectue les insert avec les données récupérées dans le tableau $data
            $annee_id = insertAnnee($bdd, $data['Annee'], $output_files['annee']);
            $pays_id = insertPays($bdd, $data['Pays'],  $output_files['pays']);

            $agriculture_id = insertAgriculture($bdd, $data['ForestArea_SqrKm'], $data['AgriculturalLand_SqrKm'], $data['ArableLand_PerOfLandArea'], $data['AgriIrrigatedLand'],  $output_files['agriculture']);
            $energie_id = insertEnergie($bdd, $data['ElecPowerConsumpt_kWhPerCapita'], $data['RenewEnergyConsumpt'], $data['RenewElecOutput'], $data['ElecProdOilSources'], $data['ElecProdNuclearSources'], $data['ElecProdNaturalGasSources'], $data['ElecProdHydroelectricSources'], $data['ElecProdCoalSources'], $data['AccessToElec_PerOfPop'],  $output_files['energie']);
            $gaz_id = insertGaz($bdd, $data['CO2Emissions_kt'], $data['CO2EmisLiquidFuelConsumpt_kt'], $data['CO2EmisGaseousFuelConsumpt_kt'], $data['MethaneEmis_ktOfCO2'], $data['TotGreenGasHouseEmis_ktOfCO2'],  $output_files['gaz']);
            $population_id = insertPopulation($bdd, $data['PopulationTotal'], $data['UrbanPopulation'],  $output_files['population']);

            insertDataset($bdd, $annee_id, $pays_id, $agriculture_id, $energie_id, $gaz_id, $population_id,  $output_files['dataset']);

        }

    }
}




function processCell(PDO $bdd, PHPExcel_Worksheet $objWorkSheet, PHPExcel_Cell $cell, $data) {
    $column = $cell->getColumn();
    $entete_colonne = $objWorkSheet->getCell($column . '1')->getValue();
    $data[$entete_colonne] = $cell->getValue();

    return $data;
}









