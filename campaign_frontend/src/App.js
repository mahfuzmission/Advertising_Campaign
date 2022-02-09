import {
  Routes,
  Route
} from "react-router-dom";
import CampaignList from './components/advList.component';
import CampaignForm from "./components/advForm.component";
import './App.css';

function App() {
  return (
    <div className="advertisement-container">
      <Routes>
        <Route path="/" element={<CampaignList />} />
        <Route path="/create" element={<CampaignForm />} />
        <Route path="/edit/:id" element={<CampaignForm />} />
        <Route path="/view/:id" element={<CampaignForm />} />
      </Routes>
    </div>
  );
}

export default App;
