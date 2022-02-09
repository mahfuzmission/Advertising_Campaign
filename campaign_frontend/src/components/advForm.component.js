import {
  Form, Input, Row,
  Col, DatePicker, Upload, Modal, Button, Image
} from 'antd'
import { UploadOutlined } from '@ant-design/icons';
import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, useLocation } from 'react-router-dom';
import moment from 'moment';
import { createCampaign, getCampaignList } from '../services/campaign.service';


const { RangePicker } = DatePicker;
const { Dragger } = Upload;


const AdvertisementForm = () => {

  const [adName, setAdName] = useState('');
  const [fromDate, setFromDate] = useState(null);
  const [toDate, setToDate] = useState(null);
  const [totalBudget, setTotalBudget] = useState(0.00);
  const [dailyBudget, setDailyBudget] = useState(0.00);
  const [imageList, setImageList] = useState([]);
  const [imagePreviewList, setImagePreviewList] = useState([]);
  const [mode, setMode] = useState('CREATE');
  const [viewBanner, setViewBanner] = useState(false);

  const navigate = useNavigate();
  const urlParams = useParams();
  const location = useLocation();

  useEffect(() => {

    if(urlParams.id !== undefined) {
      getCampaignList(urlParams.id).then((responseData) => {
        if(responseData.status === "success" && responseData.status_code === 200)
        {
          setAdName(responseData.data[0].name)
          setTotalBudget(responseData.data[0].total_budget)
          setDailyBudget(responseData.data[0].daily_budget)

          if(responseData.data[0].from !== null || responseData.data[0].from !== "")
          {
            setFromDate(moment(new Date(responseData.data[0].from)))
          }
          if(responseData.data[0].from !== null || responseData.data[0].from !== "")
          {
            setToDate(moment(new Date(responseData.data[0].to)))
          }

          if(responseData.data[0].creatives.length > 0)
          {
            setImagePreviewList(responseData.data[0].creatives)
          }
          else
          {
            setImagePreviewList([])
          }
        }
        else if(responseData.status === "failure" && responseData.status_code === 429)
        {
          alert(`Failure, ${responseData.message}!!!`)
        }
      })

      if ( location.pathname.split('/').includes('view')) {
        setMode('VIEW');
      } else if ( location.pathname.split('/').includes('edit')) {
        setMode('EDIT')
      }
    }

  }, [urlParams, location]);

  const UploadProps = {
    name: 'file',
    multiple: true,
    fileList: imageList,
    beforeUpload: ()=> {
      return false
    },
    customRequest: () => {
      return false
    },
    accept: '.png, .jpg, .jpeg',
    onChange(info) {
      setImageList(info.fileList);
    },
    onDrop(e) {
      console.log(e)
      setImageList(e.fileList);
    },
  };

  const handleName = (e) => {
    setAdName(e.target.value)
  }

  const handleDate = (e) => {
    if (e === null) {
      setFromDate(null);
      setToDate(null);
      return;
    }

    setFromDate(e[0]);
    setToDate(e[1]);
  }

  const handleTotalBudget = (e) => {
    if (e.target.value === "") {
      setTotalBudget(0.00);
      return;
    }
    setTotalBudget(parseFloat(e.target.value))
  }

  const handleDailyBudget = (e) => {
    if (e.target.value === "") {
      setDailyBudget(0.00);
      return;
    }
    setDailyBudget(parseFloat(e.target.value))
  }

  const handleCancel = () => {
    navigate('/')
  }

  const handleBannerView = () => {
    setViewBanner(!viewBanner);
  }

  const handleSubmit = () => {

    const payload = new FormData();
    payload.append('name', adName);
    payload.append('from', moment(fromDate).format('YYYY-MM-DD'));
    payload.append('to', moment(toDate).format('YYYY-MM-DD'));
    payload.append('daily_budget', dailyBudget);
    payload.append('total_budget', totalBudget);

    for (var i = 0; i < imageList.length; i++) {
      console.log(imageList[i])
      payload.append('creatives[]', imageList[i].originFileObj);
    }

    if(urlParams.id !== undefined)
    {
      payload.append('campaign_id' , urlParams.id);
    }

    createCampaign(payload).then((responseData) => {
      console.log(responseData)
      if (responseData.status_code === 200) {
        alert("Success, Campaign Updated successfully.")
        navigate('/')
      }
      else if(responseData.status_code === 201)
      {
        alert("Success, Campaign Created successfully.")
        navigate('/')
      }
      else if(responseData.status === "failure" && responseData.status_code === 429)
      {
        alert(`Failure, ${responseData.message}!!!`)
      }
      else if(responseData.status === "validation_error" && responseData.status_code === 422)
      {
        alert(`Validation Error, ${responseData.message}!!!`)
      }
      else
      {
        alert("Failure, Something Went Wrong!!!")
      }
    })
  }

  return (
    <Form
      name="basic"
      initialValues={{ remember: true }}
      autoComplete="off"
      layout='vertical'
    >
      <Row gutter={16}>
        <h2> {mode} CAMPAIGN </h2>
      </Row>
      <Row gutter={16}>
        <Col span={24}>
          <Form.Item label="Name">
            <Input value={adName} onChange={(e) => { handleName(e) }} />
          </Form.Item>
        </Col>
        <Col span={24}>
          <Form.Item label="Date">
            <RangePicker value={[fromDate, toDate]} style={{ width: '100%' }} onChange={(e) => { handleDate(e) }} />
          </Form.Item>
        </Col>
      </Row>
      <Row gutter={16}>
        <Col span={12}>
          <Form.Item label="Total Budget">
            <Input
                type="number"
                value={totalBudget}
                onChange={(e) => { handleTotalBudget(e) }}
                min={0}
                precision={2}
                step={0.01}
                maxLength={12}
            />
          </Form.Item>
        </Col>
        <Col span={12}>
          <Form.Item label="Daily Budget">
            <Input
                type="number"
                value={dailyBudget}
                onChange={(e) => { handleDailyBudget(e) }}
                min={0}
                precision={2}
                step={0.01}
                maxLength={12}
            />
          </Form.Item>
        </Col>
      </Row>
      <Row>
        <Col span={24}>
          {(mode === 'CREATE') &&
            <Upload {...UploadProps}>
              <Button icon={<UploadOutlined />}>Click to Upload</Button>
            </Upload>
          }
          {(mode === 'EDIT') &&
              <div>

                <Button onClick={handleBannerView}>View Uploaded Banners</Button>

                <Upload {...UploadProps}>
                  <Button icon={<UploadOutlined />}>Click to Upload</Button>
                </Upload>
              </div>
          }
          {(mode === 'VIEW') &&
              <Button onClick={handleBannerView}>View Uploaded Banners</Button>
          }
        </Col>
      </Row>

      <Row gutter={16}>
        <Col span={5} offset={19} style={{ display: 'flex', justifyContent: 'space-evenly' }}>
          <Button onClick={handleCancel}>Cancel</Button>
          {(mode !== 'VIEW') &&
              <Button onClick={handleSubmit} type='primary'>Confirm</Button>
          }
        </Col>
      </Row>

      {
        viewBanner && <Modal title="Basic Modal" visible={viewBanner} onCancel={handleBannerView} footer={null}>
            {
              imagePreviewList.length > 0 && imagePreviewList.map((item, index) => {
                  return <Image key={index} width={200} src={item.image_url}/>
                })
            }
        </Modal>
      }
    </Form>

  );
}

export default AdvertisementForm;
