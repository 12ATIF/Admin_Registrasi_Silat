import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Form, Button, Spinner, Alert, Table, Badge, Modal, ProgressBar } from 'react-bootstrap';
import axios from 'axios';
import moment from 'moment';
import 'moment/locale/id';

// Setup moment locale
moment.locale('id');

const PaymentManager = () => {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [payments, setPayments] = useState([]);
    const [kontingens, setKontingens] = useState([]);
    const [selectedKontingen, setSelectedKontingen] = useState('');
    const [selectedStatus, setSelectedStatus] = useState('');
    const [selectedPayment, setSelectedPayment] = useState(null);
    const [showPreviewModal, setShowPreviewModal] = useState(false);
    const [showVerifyModal, setShowVerifyModal] = useState(false);
    const [newStatus, setNewStatus] = useState('');
    const [statistics, setStatistics] = useState({
        total_tagihan: 0,
        sudah_lunas: 0,
        menunggu_verifikasi: 0,
        belum_bayar: 0
    });

    useEffect(() => {
        fetchKontingens();
        fetchPayments();
    }, []);

    useEffect(() => {
        fetchPayments();
    }, [selectedKontingen, selectedStatus]);

    const fetchKontingens = async () => {
        try {
            const response = await axios.get('/admin/kontingen');
            setKontingens(response.data.data);
        } catch (error) {
            console.error('Error fetching kontingens:', error);
        }
    };

    const fetchPayments = async () => {
        try {
            setLoading(true);
            let url = '/admin/pembayaran';
            let params = {};
            
            if (selectedKontingen) {
                params.kontingen_id = selectedKontingen;
            }
            
            if (selectedStatus) {
                params.status = selectedStatus;
            }
            
            const response = await axios.get(url, { params });
            
            setPayments(response.data.data);
            setStatistics(response.data.statistics);
            setLoading(false);
        } catch (error) {
            console.error('Error fetching payments:', error);
            setError('Terjadi kesalahan saat mengambil data pembayaran.');
            setLoading(false);
        }
    };

    const handleKontingenChange = (e) => {
        setSelectedKontingen(e.target.value);
    };

    const handleStatusChange = (e) => {
        setSelectedStatus(e.target.value);
    };

    const resetFilters = () => {
        setSelectedKontingen('');
        setSelectedStatus('');
    };

    const handlePreviewClick = (payment) => {
        setSelectedPayment(payment);
        setShowPreviewModal(true);
    };

    const handleVerifyClick = (payment) => {
        setSelectedPayment(payment);
        setNewStatus(payment.status);
        setShowVerifyModal(true);
    };

    const handleVerify = async () => {
        try {
            const response = await axios.put(`/admin/pembayaran/${selectedPayment.id}/verify`, {
                status: newStatus
            });
            
            setSuccess(response.data.message);
            setShowVerifyModal(false);
            fetchPayments();
            
            // Hide success message after 3 seconds
            setTimeout(() => {
                setSuccess('');
            }, 3000);
        } catch (error) {
            console.error('Error verifying payment:', error);
            setError(error.response?.data?.message || 'Terjadi kesalahan saat memverifikasi pembayaran.');
            setShowVerifyModal(false);
        }
    };

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    };

    const getStatusBadge = (status) => {
        switch (status) {
            case 'lunas':
                return <Badge bg="success">Lunas</Badge>;
            case 'menunggu_verifikasi':
                return <Badge bg="warning">Menunggu Verifikasi</Badge>;
            case 'belum_bayar':
                return <Badge bg="danger">Belum Bayar</Badge>;
            default:
                return <Badge bg="secondary">{status}</Badge>;
        }
    };

    const calculatePaymentProgress = () => {
        return statistics.total_tagihan > 0 
            ? Math.round((statistics.sudah_lunas / statistics.total_tagihan) * 100) 
            : 0;
    };

    return (
        <div>
            {error && (
                <Alert variant="danger" onClose={() => setError('')} dismissible>
                    {error}
                </Alert>
            )}
            
            {success && (
                <Alert variant="success" onClose={() => setSuccess('')} dismissible>
                    {success}
                </Alert>
            )}
            
            {/* Payment Statistics Cards */}
            <Row className="mb-4">
                <Col md={3} className="mb-3">
                    <Card className="border-left-primary shadow h-100 py-2">
                        <Card.Body>
                            <Row className="align-items-center">
                                <Col className="mr-2">
                                    <div className="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Tagihan
                                    </div>
                                    <div className="h5 mb-0 font-weight-bold text-gray-800">
                                        {formatCurrency(statistics.total_tagihan)}
                                    </div>
                                </Col>
                                <Col className="col-auto">
                                    <i className="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                </Col>
                            </Row>
                        </Card.Body>
                    </Card>
                </Col>
                
                <Col md={3} className="mb-3">
                    <Card className="border-left-success shadow h-100 py-2">
                        <Card.Body>
                            <Row className="align-items-center">
                                <Col className="mr-2">
                                    <div className="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Sudah Lunas
                                    </div>
                                    <div className="h5 mb-0 font-weight-bold text-gray-800">
                                        {formatCurrency(statistics.sudah_lunas)}
                                    </div>
                                </Col>
                                <Col className="col-auto">
                                    <i className="fas fa-check-circle fa-2x text-gray-300"></i>
                                </Col>
                            </Row>
                        </Card.Body>
                    </Card>
                </Col>
                
                <Col md={3} className="mb-3">
                    <Card className="border-left-warning shadow h-100 py-2">
                        <Card.Body>
                            <Row className="align-items-center">
                                <Col className="mr-2">
                                    <div className="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Menunggu Verifikasi
                                    </div>
                                    <div className="h5 mb-0 font-weight-bold text-gray-800">
                                        {statistics.menunggu_verifikasi} pembayaran
                                    </div>
                                </Col>
                                <Col className="col-auto">
                                    <i className="fas fa-clock fa-2x text-gray-300"></i>
                                </Col>
                            </Row>
                        </Card.Body>
                    </Card>
                </Col>
                
                <Col md={3} className="mb-3">
                    <Card className="border-left-danger shadow h-100 py-2">
                        <Card.Body>
                            <Row className="align-items-center">
                                <Col className="mr-2">
                                    <div className="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Belum Bayar
                                    </div>
                                    <div className="h5 mb-0 font-weight-bold text-gray-800">
                                        {statistics.belum_bayar} pembayaran
                                    </div>
                                </Col>
                                <Col className="col-auto">
                                    <i className="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                                </Col>
                            </Row>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            
            {/* Payment Progress */}
            <Card className="shadow mb-4">
                <Card.Body>
                    <h6 className="font-weight-bold mb-3">Progress Pembayaran</h6>
                    <ProgressBar now={calculatePaymentProgress()} label={`${calculatePaymentProgress()}%`} />
                </Card.Body>
            </Card>
            
            {/* Filter Card */}
            <Card className="shadow mb-4">
                <Card.Header className="py-3">
                    <h6 className="m-0 font-weight-bold">Filter Pembayaran</h6>
                </Card.Header>
                <Card.Body>
                    <Row className="mb-3">
                        <Col md={6}>
                            <Form.Group>
                                <Form.Label>Kontingen</Form.Label>
                                <Form.Select
                                    value={selectedKontingen}
                                    onChange={handleKontingenChange}
                                >
                                    <option value="">Semua Kontingen</option>
                                    {kontingens.map(kontingen => (
                                        <option key={kontingen.id} value={kontingen.id}>
                                            {kontingen.nama} - {kontingen.asal_daerah}
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </Col>
                        <Col md={6}>
                            <Form.Group>
                                <Form.Label>Status Pembayaran</Form.Label>
                                <Form.Select
                                    value={selectedStatus}
                                    onChange={handleStatusChange}
                                >
                                    <option value="">Semua Status</option>
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                    <option value="lunas">Lunas</option>
                                </Form.Select>
                            </Form.Group>
                        </Col>
                    </Row>
                    <div className="d-flex justify-content-end">
                        <Button variant="secondary" onClick={resetFilters}>
                            <i className="fas fa-sync me-1"></i> Reset Filter
                        </Button>
                    </div>
                </Card.Body>
            </Card>
            
            {/* Payments Table */}
            <Card className="shadow">
                <Card.Body>
                    {loading ? (
                        <div className="text-center py-5">
                            <Spinner animation="border" role="status">
                                <span className="visually-hidden">Loading...</span>
                            </Spinner>
                        </div>
                    ) : payments.length > 0 ? (
                        <Table responsive striped bordered hover>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kontingen</th>
                                    <th>Asal Daerah</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Total Tagihan</th>
                                    <th>Status</th>
                                    <th>Tgl. Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {payments.map(payment => (
                                    <tr key={payment.id}>
                                        <td>{payment.id}</td>
                                        <td>{payment.kontingen.nama}</td>
                                        <td>{payment.kontingen.asal_daerah}</td>
                                        <td>{payment.kontingen.pesertas_count}</td>
                                        <td>{formatCurrency(payment.total_tagihan)}</td>
                                        <td>{getStatusBadge(payment.status)}</td>
                                        <td>
                                            {payment.verified_at 
                                                ? moment(payment.verified_at).format('DD/MM/YYYY HH:mm') 
                                                : '-'
                                            }
                                        </td>
                                        <td>
                                            <div className="d-flex">
                                                {payment.bukti_transfer && (
                                                    <Button 
                                                        variant="info" 
                                                        size="sm" 
                                                        className="me-1"
                                                        onClick={() => handlePreviewClick(payment)}
                                                    >
                                                        <i className="fas fa-eye"></i>
                                                    </Button>
                                                )}
                                                <Button 
                                                    variant="primary" 
                                                    size="sm"
                                                    onClick={() => handleVerifyClick(payment)}
                                                >
                                                    <i className="fas fa-check-circle"></i>
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </Table>
                    ) : (
                        <div className="text-center py-5">
                            <p className="text-muted mb-0">Tidak ada data pembayaran yang sesuai dengan filter</p>
                        </div>
                    )}
                </Card.Body>
            </Card>
            
            {/* Preview Modal */}
            <Modal show={showPreviewModal} onHide={() => setShowPreviewModal(false)} size="lg">
                <Modal.Header closeButton>
                    <Modal.Title>Preview Bukti Transfer</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {selectedPayment?.bukti_transfer && (
                        <div className="text-center">
                            <img 
                                src={`/storage/${selectedPayment.bukti_transfer}`} 
                                alt="Bukti Transfer" 
                                className="img-fluid" 
                                style={{ maxHeight: '600px' }}
                            />
                        </div>
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShowPreviewModal(false)}>
                        Tutup
                    </Button>
                    <a 
                        href={selectedPayment?.bukti_transfer ? `/storage/${selectedPayment.bukti_transfer}` : '#'} 
                        className="btn btn-primary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i className="fas fa-download me-1"></i> Download
                    </a>
                </Modal.Footer>
            </Modal>
            
            {/* Verify Modal */}
            <Modal show={showVerifyModal} onHide={() => setShowVerifyModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>Verifikasi Pembayaran</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {selectedPayment && (
                        <>
                            <p>
                                Kontingen: <strong>{selectedPayment.kontingen.nama}</strong>
                            </p>
                            <p>
                                Total Tagihan: <strong>{formatCurrency(selectedPayment.total_tagihan)}</strong>
                            </p>
                            
                            <Form.Group className="mb-3">
                                <Form.Label>Status Pembayaran</Form.Label>
                                <Form.Select
                                    value={newStatus}
                                    onChange={(e) => setNewStatus(e.target.value)}
                                >
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                    <option value="lunas">Lunas</option>
                                </Form.Select>
                            </Form.Group>
                        </>
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShowVerifyModal(false)}>
                        Batal
                    </Button>
                    <Button variant="primary" onClick={handleVerify}>
                        Simpan Perubahan
                    </Button>
                </Modal.Footer>
            </Modal>
        </div>
    );
};

export default PaymentManager;