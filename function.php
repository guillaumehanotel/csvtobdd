<?php



function printErrorInfo(PDO $bdd, $requete, $bdd_error) {
    echo "<br><strong style='text-align: center'>PDO::errorInfo():</strong>";
    echo "<table border='1' style='border:1px solid; margin-left: auto ; margin-right : auto ; width: 60%'>"
    , "<thead>"
    , "<tr>"
    , "<th>SQL STATE</th>"
    , "<th>DRIVER ERROR CODE</th>"
    , "<th>MESSAGE</th>"
    , "<th>REQUETE</th>"
    , "</tr>"
    , "</thead>"
    , "<tbody>"
    , "<tr>";
    $erreurs = $bdd_error ? $bdd->errorInfo() : $requete->errorInfo();
    foreach ($erreurs as $key => $element) {
        $str = "";
        switch ($key) {
            case 0 :
                $str .= $element;
                break;
            case 1 :
                $str .= $element;
                break;
            case 2 :
                $str .= $element;
                break;
            default :
                $str .= "Undefined";
                break;
        }
        echo "<td><a target='_blank' href='http://www.google.com/search?q=" . urlencode($str) . "'>$str</a></td>";
    }
    echo "<td>" . $erreurs = $bdd_error ? $requete : $requete->queryString . "</td>";
    echo "</tr>"
    , "</tbody>"
    , "</table>";
}

/**
 * Prend en paramètre une requete préparée et ses paramètre
 * Execute cette requete et retourne ses résultats
 * @param PDO $bdd
 * @param PDOStatement $requete
 * @param array $param
 * @return array
 */
function getResultatsRequete(PDO $bdd, PDOStatement $requete, array $param) {
    $reponse_requete = $requete->execute($param);
    if ($reponse_requete) {
        $resultat_requete = [];
        while ($row = $requete->fetch()) {
            $resultat_requete[] = $row;
        }
        return $resultat_requete;
    } else {
        printErrorInfo($bdd, $requete, true);
        return array();
    }
}

/**
 * INSERT / UPDATE / DELETE
 * @param PDO $bdd
 * @param PDOStatement $requete
 * @param $param
 */
function executeRequest(PDO $bdd, PDOStatement $requete, $param) {
    $reponse_requete = $requete->execute($param);
    if (!$reponse_requete) {
        printErrorInfo($bdd, $requete, false);
    }
}


/**
 * Requete sur le nom du pays passé en paramètre
 */
function getPaysByName(PDO $bdd, $pays_name) {
    $requete = $bdd->prepare("SELECT * 
                FROM pays 
                WHERE pays = :pays_name");
    $param = [
        'pays_name' => $pays_name
    ];
    $sport = getResultatsRequete($bdd, $requete, $param);
    return !empty($sport) ? $sport : null;
}

/**
 * Insert un pays en BDD si il n'existe pas déjà
 * Retourne l'ID du pays
 */
function insertPays(PDO $bdd, $pays_name, $file) {
    $pays = getPaysByName($bdd, $pays_name);
    // si aucun sport avec ce nom n'est trouvé, on insert
    if (is_null($pays)) {

        $requete = $bdd->prepare("INSERT INTO pays (pays) VALUES (:pays_name)");
        $param = [
            'pays_name' => $pays_name
        ];
        executeRequest($bdd, $requete, $param);

        /*
        $request = "SELECT '" + $pays_name + "' FROM dual UNION ALL ";

        file_put_contents($file, $request, FILE_APPEND);
*/

        $pays_id = $bdd->lastInsertId();
    } else {
        $pays_id = $pays[0]['pays_id'];
    }
    return $pays_id;
}

/**
 * Requete sur l'année de l'année passé en paramètre
 */
function getAnneeByAnnee(PDO $bdd, $annee) {
    $requete = $bdd->prepare("SELECT * 
                FROM annee 
                WHERE annee = :annee");
    $param = [
        'annee' => $annee
    ];
    $annee = getResultatsRequete($bdd, $requete, $param);
    return !empty($annee) ? $annee : null;
}

/**
 * Insert un pays en BDD si il n'existe pas déjà
 * Retourne l'ID du pays
 */
function insertAnnee(PDO $bdd, $annee_date, $file) {
    $annee = getAnneeByAnnee($bdd, $annee_date);
    // si aucun sport avec ce nom n'est trouvé, on insert
    if (is_null($annee)) {
        $requete = $bdd->prepare("INSERT INTO annee (annee) VALUES (:annee_date)");
        $param = [
            'annee_date' => $annee_date
        ];
        executeRequest($bdd, $requete, $param);
        $annee_id = $bdd->lastInsertId();
    } else {
        $annee_id = $annee[0]['annee_id'];
    }
    return $annee_id;
}





function insertAgriculture(PDO $bdd, $ForestArea_SqrKm, $AgriculturalLand_SqrKm, $ArableLand_PerOfLandArea, $AgriIrrigatedLand, $file){

    $requete = $bdd->prepare("INSERT INTO agriculture (ForestArea_SqrKm, AgriculturalLand_SqrKm, ArableLand_PerOfLandArea, AgriIrrigatedLand) 
                                       VALUES (:ForestArea_SqrKm, :AgriculturalLand_SqrKm, :ArableLand_PerOfLandArea, :AgriIrrigatedLand)");
    $param = [
        'ForestArea_SqrKm' => $ForestArea_SqrKm,
        'AgriculturalLand_SqrKm' => $AgriculturalLand_SqrKm,
        'ArableLand_PerOfLandArea' => $ArableLand_PerOfLandArea,
        'AgriIrrigatedLand' => $AgriIrrigatedLand
    ];

    executeRequest($bdd, $requete, $param);
    $agriculture_id = $bdd->lastInsertId();
    return $agriculture_id;
}

function insertEnergie(PDO $bdd, $ElecPowerConsumpt_kWhPerCapita, $RenewEnergyConsumpt, $RenewElecOutput, $ElecProdOilSources, $ElecProdNuclearSources, $ElecProdNaturalGasSources, $ElecProdHydroelectricSources, $ElecProdCoalSources, $AccessToElec_PerOfPop, $file){

    $requete = $bdd->prepare("INSERT INTO energie (ElecPowerConsumpt_kWhPerCapita, RenewEnergyConsumpt, RenewElecOutput, ElecProdOilSources, ElecProdNuclearSources, ElecProdNaturalGasSources, ElecProdHydroelectricSources, ElecProdCoalSources, AccessToElec_PerOfPop) 
                                       VALUES (:ElecPowerConsumpt_kWhPerCapita, :RenewEnergyConsumpt, :RenewElecOutput, :ElecProdOilSources, :ElecProdNuclearSources, :ElecProdNaturalGasSources, :ElecProdHydroelectricSources, :ElecProdCoalSources, :AccessToElec_PerOfPop)");
    $param = [
        'ElecPowerConsumpt_kWhPerCapita' => $ElecPowerConsumpt_kWhPerCapita,
        'RenewEnergyConsumpt' => $RenewEnergyConsumpt,
        'RenewElecOutput' => $RenewElecOutput,
        'ElecProdOilSources' => $ElecProdOilSources,
        'ElecProdNuclearSources' => $ElecProdNuclearSources,
        'ElecProdNaturalGasSources' => $ElecProdNaturalGasSources,
        'ElecProdHydroelectricSources' => $ElecProdHydroelectricSources,
        'ElecProdCoalSources' => $ElecProdCoalSources,
        'AccessToElec_PerOfPop' => $AccessToElec_PerOfPop
    ];
    executeRequest($bdd, $requete, $param);
    $energie_id = $bdd->lastInsertId();
    return $energie_id;
}


function insertGaz(PDO $bdd, $CO2Emissions_kt, $CO2EmisLiquidFuelConsumpt_kt, $CO2EmisGaseousFuelConsumpt_kt, $MethaneEmis_ktOfCO2, $TotGreenGasHouseEmis_ktOfCO2, $file){

    $requete = $bdd->prepare("INSERT INTO gaz (CO2Emissions_kt, CO2EmisLiquidFuelConsumpt_kt, CO2EmisGaseousFuelConsumpt_kt, MethaneEmis_ktOfCO2, TotGreenGasHouseEmis_ktOfCO2) 
                                       VALUES (:CO2Emissions_kt, :CO2EmisLiquidFuelConsumpt_kt, :CO2EmisGaseousFuelConsumpt_kt, :MethaneEmis_ktOfCO2, :TotGreenGasHouseEmis_ktOfCO2)");
    $param = [
        'CO2Emissions_kt' => $CO2Emissions_kt,
        'CO2EmisLiquidFuelConsumpt_kt' => $CO2EmisLiquidFuelConsumpt_kt,
        'CO2EmisGaseousFuelConsumpt_kt' =>$CO2EmisGaseousFuelConsumpt_kt,
        'MethaneEmis_ktOfCO2' => $MethaneEmis_ktOfCO2,
        'TotGreenGasHouseEmis_ktOfCO2' => $TotGreenGasHouseEmis_ktOfCO2
    ];
    executeRequest($bdd, $requete, $param);
    $gaz_id = $bdd->lastInsertId();
    return $gaz_id;
}


function insertPopulation(PDO $bdd, $PopulationTotal, $UrbanPopulation, $file){
    $requete = $bdd->prepare("INSERT INTO population (PopulationTotal, UrbanPopulation) VALUES (:PopulationTotal, :UrbanPopulation)");
    $param = [
        'PopulationTotal' =>$PopulationTotal,
        'UrbanPopulation' => $UrbanPopulation
    ];
    executeRequest($bdd, $requete, $param);
    $population_id = $bdd->lastInsertId();
    return $population_id;
}


function insertDataset(PDO $bdd, $annee_id, $pays_id, $agriculture_id, $energie_id, $gaz_id, $population_id, $file) {

    $requete = $bdd->prepare("INSERT INTO dataset (annee_id, pays_id, agriculture_id, energie_id, gaz_id, population_id)
                                       VALUES (:annee_id, :pays_id, :agriculture_id, :energie_id, :gaz_id, :population_id)");
    $param = [
        'annee_id' => $annee_id,
        'pays_id' => $pays_id,
        'agriculture_id' => $agriculture_id,
        'energie_id' => $energie_id,
        'gaz_id' => $gaz_id,
        'population_id' => $population_id
    ];
    executeRequest($bdd, $requete, $param);
}









function isCellEmpty(PHPExcel_Cell $cell) {
    if (is_null($cell->getValue()) || $cell->getValue() === '') {
        return true;
    }
    return false;
}

/**
 * @param PHPExcel_Worksheet $worksheet
 * @param PHPExcel_Worksheet_Row $row
 * @var $row PHPExcel_Worksheet_Row
 * @return bool
 *
 * Fonction pour testé si une ligne est vide
 * avant : parcourt des cellules en ligne,
 * si on en trouvait une pleine, on retourne faux
 * si à la fin, aucune n'a été détecté comme rempli,
 * alors on retourne vrai
 *
 */
function isRowEmpty(PHPExcel_Worksheet $worksheet, PHPExcel_Worksheet_Row $row) {
    /* @var $cell PHPExcel_Cell */
    foreach ($row->getCellIterator() as $cell) {
        if (!isCellEmpty($cell))
            return false;
    }
    return true;
}









