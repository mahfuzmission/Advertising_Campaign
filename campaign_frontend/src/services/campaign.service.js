
const url = 'http://127.0.0.1:8000/api';

const createCampaign = async (payload) => {
    let endPoint = "/store/ad-campaigns";

    const response = await fetch(url + endPoint, {
        method: 'POST',
        body : payload
    }).then(res => res.json());

    return response;
};

const getCampaignList = async (campaign_id="") => {

    let endPoint = (campaign_id==="") ? "/ad-campaigns" : `/ad-campaigns?campaign_id=${campaign_id}`;

    const response = await fetch( url + endPoint , {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(res => res.json());

    return response;
}

export { createCampaign, getCampaignList }