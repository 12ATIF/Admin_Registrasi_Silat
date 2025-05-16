import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Form, Button, Spinner, Alert } from 'react-bootstrap';
import axios from 'axios';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    PointElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';
import { Bar, Pie } from 'react-chartjs-2';

// Register ChartJS components
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    PointElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
);

const ParticipantVisualizer = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [categoryFilters, setCategoryFilters] = useState([]);
    const [ageGroupFilters, setAgeGroupFilters] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState('');
    const [selectedAgeGroup, setSelectedAgeGroup] = useState('');
    const [selectedGender, setSelectedGender] = useState('');
    const [visualizationType, setVisualizationType] = useState('gender');
    
    // Data for charts
    const [genderData, setGenderData] = useState({
        labels: ['Putra', 'Putri'],
        datasets: [
            {
                data: [0, 0],
                backgroundColor: ['#4e73df', '#e74a3b'],
            }
        ]
    });
    
    const [categoryData, setCategoryData] = useState({
        labels: [],
        datasets: [
            {
                label: 'Jumlah Peserta',
                data: [],
                backgroundColor: '#4e73df',
            }
        ]
    });
    
    const [ageGroupData, setAgeGroupData] = useState({
        labels: [],
        datasets: [
            {
                label: 'Jumlah Peserta',
                data: [],
                backgroundColor: '#1cc88a',
            }
        ]
    });
    
    const [weightDistributionData, setWeightDistributionData] = useState({
        labels: [],
        datasets: [
            {
                label: 'Jumlah Peserta',
                data: [],
                backgroundColor: '#36b9cc',
            }
        ]
    });

    useEffect(() => {
        fetchFiltersData();
    }, []);
    
    useEffect(() => {
        fetchVisualizationData();
    }, [selectedCategory, selectedAgeGroup, selectedGender, visualizationType]);
    
    const fetchFiltersData = async () => {
        try {
            const [categoriesResponse, ageGroupsResponse] = await Promise.all([
                axios.get('/admin/kategori-lomba'),
                axios.get('/admin/kelompok-usia')
            ]);
            
            setCategoryFilters(categoriesResponse.data);
            setAgeGroupFilters(ageGroupsResponse.data);
        } catch (error) {
            console.error('Error fetching filters data:', error);
            setError('Terjadi kesalahan saat mengambil data filter.');
        }
    };
    
    const fetchVisualizationData = async () => {
        setLoading(true);
        setError('');
        
        try {
            const response = await axios.get('/admin/visualization-data', {
                params: {
                    type: visualizationType,
                    kategori_id: selectedCategory,
                    kelompok_usia_id: selectedAgeGroup,
                    jenis_kelamin: selectedGender
                }
            });
            
            const data = response.data;
            
            // Update chart data based on visualization type
            if (visualizationType === 'gender') {
                setGenderData({
                    labels: ['Putra', 'Putri'],
                    datasets: [
                        {
                            data: [data.putra_count, data.putri_count],
                            backgroundColor: ['#4e73df', '#e74a3b'],
                        }
                    ]
                });
            } else if (visualizationType === 'category') {
                setCategoryData({
                    labels: data.categories.map(item => item.nama),
                    datasets: [
                        {
                            label: 'Jumlah Peserta',
                            data: data.categories.map(item => item.count),
                            backgroundColor: '#4e73df',
                        }
                    ]
                });
            } else if (visualizationType === 'age_group') {
                setAgeGroupData({
                    labels: data.age_groups.map(item => item.nama),
                    datasets: [
                        {
                            label: 'Jumlah Peserta',
                            data: data.age_groups.map(item => item.count),
                            backgroundColor: '#1cc88a',
                        }
                    ]
                });
            } else if (visualizationType === 'weight') {
                // Create weight ranges
                const ranges = data.weight_ranges;
                setWeightDistributionData({
                    labels: ranges.map(range => `${range.min}-${range.max} kg`),
                    datasets: [
                        {
                            label: 'Jumlah Peserta',
                            data: ranges.map(range => range.count),
                            backgroundColor: '#36b9cc',
                        }
                    ]
                });
            }
            
            setLoading(false);
        } catch (error) {
            console.error('Error fetching visualization data:', error);
            setError('Terjadi kesalahan saat mengambil data visualisasi.');
            setLoading(false);
        }
    };
    
    const resetFilters = () => {
        setSelectedCategory('');
        setSelectedAgeGroup('');
        setSelectedGender('');
    };
    
    const renderChart = () => {
        if (loading) {
            return (
                <div className="text-center py-5">
                    <Spinner animation="border" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </Spinner>
                </div>
            );
        }
        
        if (error) {
            return (
                <Alert variant="danger">
                    {error}
                </Alert>
            );
        }
        
        switch (visualizationType) {
            case 'gender':
                return (
                    <div className="chart-container" style={{ position: 'relative', height: '400px' }}>
                        <Pie 
                            data={genderData} 
                            options={{
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    },
                                    title: {
                                        display: true,
                                        text: 'Distribusi Peserta Berdasarkan Jenis Kelamin',
                                        font: { size: 16 }
                                    }
                                }
                            }} 
                        />
                    </div>
                );
            case 'category':
                return (
                    <div className="chart-container" style={{ position: 'relative', height: '400px' }}>
                        <Bar 
                            data={categoryData} 
                            options={{
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Distribusi Peserta Berdasarkan Kategori Lomba',
                                        font: { size: 16 }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }} 
                        />
                    </div>
                );
            case 'age_group':
                return (
                    <div className="chart-container" style={{ position: 'relative', height: '400px' }}>
                        <Bar 
                            data={ageGroupData} 
                            options={{
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Distribusi Peserta Berdasarkan Kelompok Usia',
                                        font: { size: 16 }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }} 
                        />
                    </div>
                );
            case 'weight':
                return (
                    <div className="chart-container" style={{ position: 'relative', height: '400px' }}>
                        <Bar 
                            data={weightDistributionData} 
                            options={{
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Distribusi Peserta Berdasarkan Berat Badan',
                                        font: { size: 16 }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }} 
                        />
                    </div>
                );
            default:
                return null;
        }
    };

    return (
        <div>
            <Card className="shadow mb-4">
                <Card.Header className="py-3 d-flex justify-content-between align-items-center">
                    <h6 className="m-0 font-weight-bold">Visualisasi Data Peserta</h6>
                </Card.Header>
                <Card.Body>
                    <Row className="mb-3">
                        <Col md={3}>
                            <Form.Group>
                                <Form.Label>Jenis Visualisasi</Form.Label>
                                <Form.Select
                                    value={visualizationType}
                                    onChange={(e) => setVisualizationType(e.target.value)}
                                >
                                    <option value="gender">Berdasarkan Jenis Kelamin</option>
                                    <option value="category">Berdasarkan Kategori Lomba</option>
                                    <option value="age_group">Berdasarkan Kelompok Usia</option>
                                    <option value="weight">Berdasarkan Berat Badan</option>
                                </Form.Select>
                            </Form.Group>
                        </Col>
                        <Col md={3}>
                            <Form.Group>
                                <Form.Label>Kategori Lomba</Form.Label>
                                <Form.Select
                                    value={selectedCategory}
                                    onChange={(e) => setSelectedCategory(e.target.value)}
                                >
                                    <option value="">Semua Kategori</option>
                                    {categoryFilters.map(category => (
                                        <option key={category.id} value={category.id}>
                                            {category.nama}
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </Col>
                        <Col md={3}>
                            <Form.Group>
                                <Form.Label>Kelompok Usia</Form.Label>
                                <Form.Select
                                    value={selectedAgeGroup}
                                    onChange={(e) => setSelectedAgeGroup(e.target.value)}
                                >
                                    <option value="">Semua Kelompok Usia</option>
                                    {ageGroupFilters.map(ageGroup => (
                                        <option key={ageGroup.id} value={ageGroup.id}>
                                            {ageGroup.nama} ({ageGroup.rentang_usia_min}-{ageGroup.rentang_usia_max} tahun)
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </Col>
                        <Col md={3}>
                            <Form.Group>
                                <Form.Label>Jenis Kelamin</Form.Label>
                                <Form.Select
                                    value={selectedGender}
                                    onChange={(e) => setSelectedGender(e.target.value)}
                                >
                                    <option value="">Semua</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </Form.Select>
                            </Form.Group>
                        </Col>
                    </Row>
                    <div className="d-flex justify-content-end mb-3">
                        <Button variant="secondary" onClick={resetFilters}>
                            <i className="fas fa-sync me-1"></i> Reset Filter
                        </Button>
                    </div>
                    
                    {renderChart()}
                </Card.Body>
            </Card>
        </div>
    );
};

export default ParticipantVisualizer;