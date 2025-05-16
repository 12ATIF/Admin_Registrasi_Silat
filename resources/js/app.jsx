import './bootstrap';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { Container } from 'react-bootstrap';
import DashboardStats from './components/DashboardStats';
import PesertaDataTable from './components/PesertaDataTable';
import ParticipantVisualizer from './components/ParticipantVisualizer';
import PertandinganSchedule from './components/PertandinganSchedule';
import PaymentManager from './components/PaymentManager';

const App = () => {
    // Identify the current page based on window location
    const getCurrentPage = () => {
        const path = window.location.pathname;
        
        if (path.includes('/admin/dashboard')) {
            return 'dashboard';
        } else if (path.includes('/admin/peserta')) {
            return 'peserta';
        } else if (path.includes('/admin/visualization')) {
            return 'visualization';
        } else if (path.includes('/admin/schedule')) {
            return 'schedule';
        } else if (path.includes('/admin/payments')) {
            return 'payments';
        }
        
        return null;
    };
    
    const renderComponent = () => {
        const currentPage = getCurrentPage();
        
        switch (currentPage) {
            case 'dashboard':
                return <DashboardStats />;
            case 'peserta':
                return <PesertaDataTable />;
            case 'visualization':
                return <ParticipantVisualizer />;
            case 'schedule':
                return <PertandinganSchedule />;
            case 'payments':
                return <PaymentManager />;
            default:
                return null;
        }
    };
    
    return (
        <Container fluid>
            {renderComponent()}
        </Container>
    );
};

// Only render the React components if the corresponding element exists in the page
const mountPoints = [
    { id: 'dashboard-app', component: <DashboardStats /> },
    { id: 'peserta-app', component: <PesertaDataTable /> },
    { id: 'visualization-app', component: <ParticipantVisualizer /> },
    { id: 'schedule-app', component: <PertandinganSchedule /> },
    { id: 'payments-app', component: <PaymentManager /> },
];

mountPoints.forEach(({ id, component }) => {
    const element = document.getElementById(id);
    if (element) {
        const root = createRoot(element);
        root.render(component);
    }
});

// If using Laravel Vite, add this line or equivalent based on your bundler
// import.meta.glob(['../images/**']);