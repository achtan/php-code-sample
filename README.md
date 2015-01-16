# Ukazka mojej prace ...

## /Bazaar
priecinok obsahuje Modelovu vrstvu 2 entit 
- Asset
- Bazaar

*v projekte vyuzivam LeanMapper*

koncovy suborov:
- *.php - samotna entita
- *Repository.php - reposotry pre entitu
- *Model.php - samotny model nad entitou, obsahuje rozne metody na vyber, vkladanie alebo upravovanie
- *Criteria.php - je v podstate QueryObject pre entitu

ukazka pouzitia QueryObjektu:
````php
$criteria = (new Bazaar\AssetCriteria())
	->type($assetType)
	->bazaar($bazaar)
	->returnPairs();

$adTypeAssets = $assetModel->findBy($criteria);
````

## /Bridge
sa stara komunikaciu medzy mojou a externou appkou. `Bridge::processRequest()` spracuje a vykona request na externu aplikaciu

## /Api
obsahuje triedy na rozne typy konunikacie (pridat, zmazat a zobrazit info o inzerate)

Kazdy request ma aj svoj vlastny response objekt cize pri volani API je zrejme ze ake data mozem ocakavat

pouzitie:

```php
// prepare QueryObject
$adCriteria = (new AdCriteria())
	->status(Ad::STATUS_FINISHED)
	->publishedIn($bazaar)
	->returnOnlyIds();

// get ads-ids from DB
$adsIds = $this->adModel->findBy($adCriteria);

// create api request object
$request = $this->adListInfoRequestFactory->create($adsIds);

// create bridge object
$bridge = $this->bridgeFactory->create($bazaar);

// send request and get response from it
$response = $bridge->processRequest($request);

if(!$response->isOk()) {
	throw new BadResponseException('errro');
}

$list = $response->getList());
```