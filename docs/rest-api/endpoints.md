# REST API Endpoints

## Authentication

Documentation on authentication WooCommerce REST API requests can be found [here](https://github.com/woocommerce/woocommerce/blob/trunk/docs/rest-api/getting-started.md#make-a-basic-request). The credentials used for authentication can be found in the WooCommerce > Settings > Advanced > REST API page of the main GoodBids site admin (or in 1Pass).

## WooCommerce

### POST `/wc/v3/credentials`

Used to generate WooCommerce API credentials for a Nonprofit site. If credentials already exist, they will be revoked and new credentials will be created. This endpoint is only available on the Main GoodBids site.

#### Parameters

| Parameter | Type   | Description                       |
|-----------|--------|-----------------------------------|
| domain    | string | The domain of the nonprofit site. |

#### Response

The response will contain the WooCommerce API credentials that can be used for the given site.
```json
{
	"key": "ck_XXXXX",
	"secret": "cs_XXXXX"
}
```

## GoodBids

### GET `/wp/v2/auction/<id>/details`

Retrieves the Auction details for the given Auction ID.

#### Parameters

None.

#### Response

The response will contain the details shown below.
```json
{
  "auctionStatus": "string",
  "socketUrl": "string",
  "bidUrl": "string",
  "accountUrl": "string", 
  "startTime": "string",
  "endTime": "string",
  "totalBids": "number",
  "totalRaised": "float",
  "currentBid": "float",
  "lastBid": "number"
}
```
