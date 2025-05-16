import React, { useState, useEffect } from 'react';
import DataTable from 'react-data-table-component';
import { Modal, Button, Form, Spinner, Alert } from 'react-bootstrap';
import axios from 'axios';

const PesertaDataTable = () => {
    const [peserta, setPeserta] = useState([]);
    const [loading, setLoading] = useState(true);
    const [totalRows, setTotalRows] = useState(0);
    const [perPage, setPerPage] = useState(10);
    const [currentPage, setCurrentPage] = useState(1);
    const [filterText, setFilterText] = useState('');
    const [resetPaginationToggle, setResetPaginationToggle] = useState(false);
    const [selectedStatus, setSelectedStatus] = useState('');
    const [selectedKategori, setSelectedKategori] = useState('');
    const [selectedKelompokUsia, setSelectedKelompokUsia] = useState('');
    const [categories, setCategories] = useState([]);
    const [ageGroups, setAgeGroups] = useState([]);
    const [selectedPeserta, setSelectedPeserta] = useState(null);
    const [showVerifyModal, setShowVerifyModal] = useState(false);
    const [showClassModal, setShowClassModal] = useState(false);
    const [availableClasses, setAvailableClasses] = useState([]);
    const [selectedClass, setSelectedClass] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    const fetchPeserta = async (page, size = perPage) => {
        setLoading(true);
        
        try {
            const response = await axios.get('/admin/peserta', {
                params: { 
                    page,
                    per_page: size,
                    search: filterText,
                    status_verifikasi: selectedStatus,
                    kategori_id: selectedKategori,
                    kelompok_usia_id: selectedKelompokUsia
                }
            });
            
            setPeserta(response.data.data);
            setTotalRows(response.data.total);
            setLoading(false);
        } catch (error) {
            console.error('Error fetching data:', error);
            setLoading(false);
        }
    };
    
    const fetchCategories = async () => {
        try {
            const response = await axios.get('/admin/kategori-lomba');
            setCategories(response.data);
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    };
    
    const fetchAgeGroups = async () => {
        try {
            const response = await axios.get('/admin/kelompok-usia');
            setAgeGroups(response.data);
        } catch (error) {
            console.error('Error fetching age groups:', error);
        }
    };
    
    useEffect(() => {
        fetchCategories();
        fetchAgeGroups();
    }, []);
    
    useEffect(() => {
        fetchPeserta(currentPage);
    }, [currentPage, perPage, filterText, selectedStatus, selectedKategori, selectedKelompokUsia]);
    
    const handlePageChange = page => {
        setCurrentPage(page);
    };
    
    const handlePerRowsChange = async (newPerPage, page) => {
        setPerPage(newPerPage);
    };
    
    const handleFilter = e => {
        const value = e.target.value || '';
        setFilterText(value);
        setResetPaginationToggle(!resetPaginationToggle);
        setCurrentPage(1);
    };
    
    const handleStatusFilter = e => {
        setSelectedStatus(e.target.value);
        setCurrentPage(1);
    };
    
    const handleCategoryFilter = e => {
        setSelectedKategori(e.target.value);
        setCurrentPage(1);
    };
    
    const handleAgeGroupFilter = e => {
        setSelectedKelompokUsia(e.target.value);
        setCurrentPage(1);
    };
    
    const handleVerify = (peserta, status) => {
        setSelectedPeserta(peserta);
        setSelectedStatus(status);
        setShowVerifyModal(true);
    };
    
    const confirmVerify = async () => {
        try {
            const response = await axios.put(`/admin/peserta/${selectedPeserta.id}/verify`, {
                status_verifikasi: selectedStatus
            });
            
            setSuccessMessage(response.data.message);
            setShowVerifyModal(false);
            fetchPeserta(currentPage);
            
            // Auto-hide success message after 3 seconds
            setTimeout(() => {
                setSuccessMessage('');
            }, 3000);
        } catch (error) {
            setErrorMessage(error.response?.data?.message || 'Terjadi kesalahan saat memverifikasi peserta.');
        }
    };
    
    const handleChangeClass = async (peserta) => {
        setSelectedPeserta(peserta);
        setShowClassModal(true);
        
        try {
            // Fetch available classes based on peserta's age group and gender
            const response = await axios.get('/admin/kelas-tanding', {
                params: {
                    kelompok_usia_id: peserta.kelompok_usia_id,
                    jenis_kelamin: peserta.jenis_kelamin === 'L' ? 'putra' : 'putri',
                    expectsJson: true
                }
            });
            
            setAvailableClasses(response.data.data);
            setSelectedClass(peserta.kelas_tanding_id || '');
        } catch (error) {
            console.error('Error fetching kelas tanding:', error);
        }
    };
    
    const confirmChangeClass = async () => {
        try {
            const response = await axios.put(`/admin/peserta/${selectedPeserta.id}/override-class`, {
                kelas_tanding_id: selectedClass
            });
            
            setSuccessMessage(response.data.message);
            setShowClassModal(false);
            fetchPeserta(currentPage);
            
            // Auto-hide success message after 3 seconds
            setTimeout(() => {
                setSuccessMessage('');
            }, 3000);
        } catch (error) {
            setErrorMessage(error.response?.data?.message || 'Terjadi kesalahan saat mengubah kelas tanding.');
        }
    };
    
    const resetFilters = () => {
        setFilterText('');
        setSelectedStatus('');
        setSelectedKategori('');
        setSelectedKelompokUsia('');
        setCurrentPage(1);
    };
    
    const columns = [
        {
            name: 'Nama',
            selector: row => row.nama,
            sortable: true,
            grow: 1.5,
            wrap: true
        },
        {
            name: 'Jenis Kelamin',
            selector: row => row.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            sortable: true,
        },
        {
            name: 'Tanggal Lahir',
            selector: row => new Date(row.tanggal_lahir).toLocaleDateString('id-ID'),
            sortable: true,
        },
        {
            name: 'Berat Badan',
            selector: row => `${row.berat_badan} kg`,
            sortable: true,
        },
        {
            name: 'Kontingen',
            selector: row => row.kontingen.nama,
            sortable: true,
            wrap: true
        },
        {
            name: 'Kategori',
            selector: row => `${row.subkategori_lomba.kategori_lomba.nama} - ${row.subkategori_lomba.nama}`,
            sortable: true,
            wrap: true,
            grow: 1.5
        },
        {
            name: 'Kelas',
            selector: row => row.kelas_tanding ? row.kelas_tanding.label_keterangan : '-',
            sortable: true,
            wrap: true
        },
        {
            name: 'Status',
            selector: row => row.status_verifikasi,
            sortable: true,
            cell: row => {
                const getBadgeClass = status => {
                    switch (status) {
                        case 'valid':
                            return 'bg-success';
                        case 'pending':
                            return 'bg-warning';
                        case 'tidak_valid':
                            return 'bg-danger';
                        default:
                            return 'bg-secondary';
                    }
                };
                
                return (
                    <span className={`badge ${getBadgeClass(row.status_verifikasi)}`}>
                        {row.status_verifikasi === 'valid' ? 'Valid' : 
                         row.status_verifikasi === 'pending' ? 'Pending' : 'Tidak Valid'}
                    </span>
                );
            }
        },
        {
            name: 'Aksi',
            cell: row => (
                <div className="btn-group">
                    <button
                        className="btn btn-sm btn-success"
                        onClick={() => handleVerify(row, 'valid')}
                    >
                        <i className="fas fa-check"></i>
                    </button>
                    <button
                        className="btn btn-sm btn-danger"
                        onClick={() => handleVerify(row, 'tidak_valid')}
                    >
                        <i className="fas fa-times"></i>
                    </button>
                    <button
                        className="btn btn-sm btn-warning"
                        onClick={() => handleChangeClass(row)}
                    >
                        <i className="fas fa-exchange-alt"></i>
                    </button>
                </div>
            ),
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
        }
    ];
    
    return (
        <div>
            {successMessage && (
                <Alert variant="success" onClose={() => setSuccessMessage('')} dismissible>
                    {successMessage}
                </Alert>
            )}
            
            {errorMessage && (
                <Alert variant="danger" onClose={() => setErrorMessage('')} dismissible>
                    {errorMessage}
                </Alert>
            )}
            
            <div className="card shadow mb-4">
                <div className="card-header py-3">
                    <h6 className="m-0 font-weight-bold">Filter Peserta</h6>
                </div>
                <div className="card-body">
                    <div className="row mb-3">
                        <div className="col-md-3 mb-2">
                            <Form.Group>
                                <Form.Label>Cari Peserta</Form.Label>
                                <Form.Control
                                    type="text"
                                    placeholder="Nama peserta..."
                                    value={filterText}
                                    onChange={handleFilter}
                                />
                            </Form.Group>
                        </div>
                        <div className="col-md-3 mb-2">
                            <Form.Group>
                                <Form.Label>Status Verifikasi</Form.Label>
                                <Form.Select
                                    value={selectedStatus}
                                    onChange={handleStatusFilter}
                                >
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="valid">Valid</option>
                                    <option value="tidak_valid">Tidak Valid</option>
                                </Form.Select>
                            </Form.Group>
                        </div>
                        <div className="col-md-3 mb-2">
                            <Form.Group>
                                <Form.Label>Kategori Lomba</Form.Label>
                                <Form.Select
                                    value={selectedKategori}
                                    onChange={handleCategoryFilter}
                                >
                                    <option value="">Semua Kategori</option>
                                    {categories.map(category => (
                                        <option key={category.id} value={category.id}>
                                            {category.nama}
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </div>
                        <div className="col-md-3 mb-2">
                            <Form.Group>
                                <Form.Label>Kelompok Usia</Form.Label>
                                <Form.Select
                                    value={selectedKelompokUsia}
                                    onChange={handleAgeGroupFilter}
                                >
                                    <option value="">Semua Kelompok Usia</option>
                                    {ageGroups.map(ageGroup => (
                                        <option key={ageGroup.id} value={ageGroup.id}>
                                            {ageGroup.nama} ({ageGroup.rentang_usia_min}-{ageGroup.rentang_usia_max} tahun)
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </div>
                    </div>
                    <div className="d-flex justify-content-end">
                        <Button variant="secondary" onClick={resetFilters}>
                            <i className="fas fa-sync me-1"></i> Reset Filter
                        </Button>
                    </div>
                </div>
            </div>
            
            <div className="card shadow">
                <div className="card-body">
                    <DataTable
                        title="Daftar Peserta"
                        columns={columns}
                        data={peserta}
                        progressPending={loading}
                        pagination
                        paginationServer
                        paginationTotalRows={totalRows}
                        paginationDefaultPage={currentPage}
                        paginationResetDefaultPage={resetPaginationToggle}
                        onChangeRowsPerPage={handlePerRowsChange}
                        onChangePage={handlePageChange}
                        progressComponent={<Spinner animation="border" />}
                        highlightOnHover
                        persistTableHead
                        responsive
                    />
                </div>
            </div>
            
            {/* Verification Modal */}
            <Modal show={showVerifyModal} onHide={() => setShowVerifyModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>Konfirmasi Verifikasi</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {selectedPeserta && (
                        <p>
                            Apakah Anda yakin ingin {selectedStatus === 'valid' ? 'memvalidasi' : 'tidak memvalidasi'} peserta <strong>{selectedPeserta.nama}</strong>?
                        </p>
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShowVerifyModal(false)}>
                        Batal
                    </Button>
                    <Button 
                        variant={selectedStatus === 'valid' ? 'success' : 'danger'} 
                        onClick={confirmVerify}
                    >
                        Ya, {selectedStatus === 'valid' ? 'Validasi' : 'Tidak Validasi'} Peserta
                    </Button>
                </Modal.Footer>
            </Modal>
            
            {/* Change Class Modal */}
            <Modal show={showClassModal} onHide={() => setShowClassModal(false)}>
                <Modal.Header closeButton>
                    <Modal.Title>Ubah Kelas Tanding</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    {selectedPeserta && (
                        <>
                            <p>
                                Ubah kelas tanding untuk peserta <strong>{selectedPeserta.nama}</strong> ({selectedPeserta.berat_badan} kg)
                            </p>
                            <Form.Group className="mb-3">
                                <Form.Label>Kelas Tanding</Form.Label>
                                <Form.Select
                                    value={selectedClass}
                                    onChange={(e) => setSelectedClass(e.target.value)}
                                >
                                    <option value="">Pilih Kelas Tanding</option>
                                    {availableClasses.map(kelas => (
                                        <option key={kelas.id} value={kelas.id}>
                                            {kelas.label_keterangan} ({kelas.berat_min}-{kelas.berat_max} kg)
                                        </option>
                                    ))}
                                </Form.Select>
                                <Form.Text className="text-muted">
                                    Pilih kelas tanding yang sesuai dengan berat badan peserta.
                                </Form.Text>
                            </Form.Group>
                        </>
                    )}
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={() => setShowClassModal(false)}>
                        Batal
                    </Button>
                    <Button 
                        variant="primary" 
                        onClick={confirmChangeClass}
                        disabled={!selectedClass}
                    >
                        Simpan Perubahan
                    </Button>
                </Modal.Footer>
            </Modal>
        </div>
    );
};

export default PesertaDataTable;