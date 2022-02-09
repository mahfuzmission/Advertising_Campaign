import React, {useEffect, useState} from "react";
import { Layout, Row, Col, Button, Table } from "antd";
import { useNavigate } from 'react-router-dom';

import { getCampaignList } from '../services/campaign.service';

const { Content } = Layout;

const CampaignList = () => {
  const navigate = useNavigate();
  const [data, setData] = useState([]);

  const columns = [
    {
      title: "Name",
      dataIndex: "name",
      key: "name",
      render: (text) => <a>{text}</a>,
    },
    {
      title: "Start Date",
      dataIndex: "from",
      key: "from",
    },
    {
      title: "End Date",
      dataIndex: "to",
      key: "to",
    },
    {
      title: "Daily Budget",
      dataIndex: "daily_budget",
      key: "daily_budget",
    },
    {
      title: "Total Budget",
      dataIndex: "total_budget",
      key: "total_budget",
    },
    {
      title: "Action",
      key: "action",
      render: (value, rowObj) => (
        <>
          <Button type="primary" onClick={() => { handleAction(rowObj.id, 'edit') }}>Edit</Button>
          <Button onClick={() => { handleAction(rowObj.id, 'view') }}>Preview</Button>
        </>
      ),
    },
  ];


  useEffect(() => {

    let campaignList = [];

    getCampaignList().then((responseData) => {
      if(responseData.status === "success" && responseData.status_code === 200)
      {
        campaignList = responseData.data
      }
      else if(responseData.status === "failure" && responseData.status_code === 429)
      {
        alert(`Failure, ${responseData.message}!!!`)
      }
      else
      {
        alert("Failure, Something Went Wrong!!!")
      }

      setData(campaignList)
    })

  }, data)

  const handleAction = (id, path) => {
    navigate(`/${path}/${id}`)
  }

  return (
    <Content>
      <Row gutter={16}>
        <Col span={12}>
          <h3>List of advertisement</h3>
        </Col>
        <Col span={2} offset={10}>
          <Button type="link" href="/create">Create Advertisement</Button>
        </Col>
      </Row>
      <Row gutter={16}>
        <Col span={24}>
          <Table columns={columns} dataSource={data} />
        </Col>
      </Row>
    </Content>
  );
};

export default CampaignList;
