import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Form, Button, Spinner, Alert, Table, Badge } from 'react-bootstrap';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import moment from 'moment';
import 'moment/locale/id';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import axios from 'axios';

// Setup moment locale
moment.locale('id');
const localizer = momentLocalizer(moment);

const PertandinganSchedule = () => {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [pertandingans, setPertandingans] = useState([]);
    const [selectedPertandingan, setSelectedPertandingan] = useState('');
    const [jadwalEvents, setJadwalEvents] = useState([]);
    const [subkategoris, setSubkategoris] = useState([]);
    const [kelompokUsias, setKelompokUsias] = useState([]);
    const [selectedSubkategori, setSelectedSubkategori] = useState('');
    const [selectedKelompokUsia, setSelectedKelompokUsia] = useState('');
    const [calendarView, setCalendarView] = useState('month');
    const [jadwals, setJadwals] = useState([]);
    const [modalVisible, setModalVisible] = useState(false);
    const [selectedEvent, setSelectedEvent] = useState(null);

    useEffect(() => {
        fetchPertandingans();
        fetchFilterOptions();
    }, []);

    useEffect(() => {
        if (selectedPertandingan) {
            fetchJadwals();
        }
    }, [selectedPertandingan, selectedSubkategori, selectedKelompokUsia]);

    const fetchPertandingans = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/admin/pertandingan');
            setPertandingans(response.data.data);
            
            // Select the first pertandingan by default if available
            if (response.data.data.length > 0) {
                setSelectedPertandingan(response.data.data[0].id);
            }
            
            setLoading(false);
        } catch (error) {
            console.error('Error fetching pertandingans:', error);
            setError('Terjadi kesalahan saat mengambil data pertandingan.');
            setLoading(false);
        }
    };

    const fetchFilterOptions = async () => {
        try {
            const [subkategoriResponse, kelompokUsiaResponse] = await Promise.all([
                axios.get('/admin/subkategori-lomba'),
                axios.get('/admin/kelompok-usia')
            ]);
            
            setSubkategoris(subkategoriResponse.data);
            setKelompokUsias(kelompokUsiaResponse.data);
        } catch (error) {
            console.error('Error fetching filter options:', error);
        }
    };

    const fetchJadwals = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/admin/jadwal-pertandingan', {
                params: {
                    pertandingan_id: selectedPertandingan,
                    subkategori_id: selectedSubkategori,
                    kelompok_usia_id: selectedKelompokUsia
                }
            });
            
            setJadwals(response.data.data);
            
            // Convert jadwals to calendar events
            const events = response.data.data.map(jadwal => {
                const startTime = moment(`${jadwal.tanggal} ${jadwal.waktu_mulai}`).toDate();
                const endTime = jadwal.waktu_selesai 
                    ? moment(`${jadwal.tanggal} ${jadwal.waktu_selesai}`).toDate()
                    : moment(`${jadwal.tanggal} ${jadwal.waktu_mulai}`).add(1, 'hours').toDate();
                
                return {
                    id: jadwal.id,
                    title: `${jadwal.subkategori_lomba.nama} - ${jadwal.kelompok_usia.nama}`,
                    start: startTime,
                    end: endTime,
                    allDay: false,
                    resource: jadwal
                };
            });
            
            setJadwalEvents(events);
            setLoading(false);
        } catch (error) {
            console.error('Error fetching jadwals:', error);
            setError('Terjadi kesalahan saat mengambil data jadwal.');
            setLoading(false);
        }
    };

    const handlePertandinganChange = (e) => {
        setSelectedPertandingan(e.target.value);
    };

    const handleSubkategoriChange = (e) => {
        setSelectedSubkategori(e.target.value);
    };

    const handleKelompokUsiaChange = (e) => {
        setSelectedKelompokUsia(e.target.value);
    };

    const resetFilters = () => {
        setSelectedSubkategori('');
        setSelectedKelompokUsia('');
    };

    const handleEventSelect = (event) => {
        setSelectedEvent(event.resource);
        setModalVisible(true);
    };

    // Custom event component to add styling
    const eventStyleGetter = (event) => {
        // Get different colors based on subkategori
        const colors = [
            { bg: '#4e73df', text: '#fff' }, // blue
            { bg: '#1cc88a', text: '#fff' }, // green
            { bg: '#36b9cc', text: '#fff' }, // cyan
            { bg: '#f6c23e', text: '#000' }, // yellow
            { bg: '#e74a3b', text: '#fff' }, // red
            { bg: '#5a5c69', text: '#fff' }, // gray
        ];
        
        // Use event ID or another property to get consistent colors
        const colorIndex = event.id % colors.length;
        
        return {
            style: {
                backgroundColor: colors[colorIndex].bg,
                color: colors[colorIndex].text,
                borderRadius: '4px',
                border: 'none',
                padding: '2px 5px'
            }
        };
    };

    if (loading && pertandingans.length === 0) {
        return (
            <div className="text-center py-5">
                <Spinner animation="border" role="status">
                    <span className="visually-hidden">Loading...</span>
                </Spinner>
            </div>
        );
    }

    return (
        <div>
            {error && (
                <Alert variant="danger" onClose={() => setError('')} dismissible>
                    {error}
                </Alert>
            )}
            
            <Card className="shadow mb-4">
                <Card.Header className="py-3">
                    <h6 className="m-0 font-weight-bold">Filter Jadwal</h6>
                </Card.Header>
                <Card.Body>
                    <Row className="mb-3">
                        <Col md={4}>
                            <Form.Group>
                                <Form.Label>Pertandingan</Form.Label>
                                <Form.Select
                                    value={selectedPertandingan}
                                    onChange={handlePertandinganChange}
                                >
                                    <option value="">Pilih Pertandingan</option>
                                    {pertandingans.map(pertandingan => (
                                        <option key={pertandingan.id} value={pertandingan.id}>
                                            {pertandingan.nama_event} - {moment(pertandingan.tanggal_event).format('DD/MM/YYYY')}
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </Col>
                        <Col md={4}>
                            <Form.Group>
                                <Form.Label>Subkategori</Form.Label>
                                <Form.Select
                                    value={selectedSubkategori}
                                    onChange={handleSubkategoriChange}
                                >
                                    <option value="">Semua Subkategori</option>
                                    {subkategoris.map(subkategori => (
                                        <option key={subkategori.id} value={subkategori.id}>
                                            {subkategori.kategori_lomba.nama} - {subkategori.nama}
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </Col>
                        <Col md={4}>
                            <Form.Group>
                                <Form.Label>Kelompok Usia</Form.Label>
                                <Form.Select
                                    value={selectedKelompokUsia}
                                    onChange={handleKelompokUsiaChange}
                                >
                                    <option value="">Semua Kelompok Usia</option>
                                    {kelompokUsias.map(usia => (
                                        <option key={usia.id} value={usia.id}>
                                            {usia.nama} ({usia.rentang_usia_min}-{usia.rentang_usia_max} tahun)
                                        </option>
                                    ))}
                                </Form.Select>
                            </Form.Group>
                        </Col>
                    </Row>
                    <div className="d-flex justify-content-between mb-3">
                        <div>
                            <Button 
                                variant={calendarView === 'month' ? 'primary' : 'outline-primary'} 
                                className="me-2"
                                onClick={() => setCalendarView('month')}
                            >
                                <i className="fas fa-calendar-alt me-1"></i> Bulan
                            </Button>
                            <Button 
                                variant={calendarView === 'week' ? 'primary' : 'outline-primary'} 
                                className="me-2"
                                onClick={() => setCalendarView('week')}
                            >
                                <i className="fas fa-calendar-week me-1"></i> Minggu
                            </Button>
                            <Button 
                                variant={calendarView === 'day' ? 'primary' : 'outline-primary'} 
                                className="me-2"
                                onClick={() => setCalendarView('day')}
                            >
                                <i className="fas fa-calendar-day me-1"></i> Hari
                            </Button>
                            <Button 
                                variant={calendarView === 'agenda' ? 'primary' : 'outline-primary'} 
                                className="me-2"
                                onClick={() => setCalendarView('agenda')}
                            >
                                <i className="fas fa-list me-1"></i> Agenda
                            </Button>
                        </div>
                        <Button variant="secondary" onClick={resetFilters}>
                            <i className="fas fa-sync me-1"></i> Reset Filter
                        </Button>
                    </div>
                </Card.Body>
            </Card>
            
            <Card className="shadow">
                <Card.Body>
                    {loading ? (
                        <div className="text-center py-5">
                            <Spinner animation="border" role="status">
                                <span className="visually-hidden">Loading...</span>
                            </Spinner>
                        </div>
                    ) : (
                        <Calendar
                            localizer={localizer}
                            events={jadwalEvents}
                            startAccessor="start"
                            endAccessor="end"
                            style={{ height: 700 }}
                            view={calendarView}
                            onView={(view) => setCalendarView(view)}
                            eventPropGetter={eventStyleGetter}
                            onSelectEvent={handleEventSelect}
                            messages={{
                                month: 'Bulan',
                                week: 'Minggu',
                                day: 'Hari',
                                agenda: 'Agenda',
                                date: 'Tanggal',
                                time: 'Waktu',
                                event: 'Acara',
                                allDay: 'Sepanjang hari',
                                previous: 'Sebelumnya',
                                next: 'Selanjutnya',
                                today: 'Hari Ini',
                                noEventsInRange: 'Tidak ada jadwal dalam rentang ini.'
                            }}
                            formats={{
                                dayHeaderFormat: (date) => moment(date).format('dddd, D MMMM YYYY'),
                                dayFormat: (date) => moment(date).format('dddd, D/M'),
                                weekdayFormat: (date) => moment(date).format('dddd'),
                                timeGutterFormat: (date) => moment(date).format('HH:mm'),
                                eventTimeRangeFormat: ({ start, end }) => {
                                    return `${moment(start).format('HH:mm')} - ${moment(end).format('HH:mm')}`;
                                }
                            }}
                        />
                    )}
                </Card.Body>
            </Card>
            
            {/* Modal Detail Jadwal */}
            {selectedEvent && (
                <div className={`modal fade ${modalVisible ? 'show' : ''}`} 
                    tabIndex="-1" 
                    role="dialog"
                    style={{ display: modalVisible ? 'block' : 'none' }}
                >
                    <div className="modal-dialog modal-lg" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">Detail Jadwal Pertandingan</h5>
                                <button type="button" className="btn-close" onClick={() => setModalVisible(false)} aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                <Row>
                                    <Col md={6}>
                                        <h6>Informasi Jadwal</h6>
                                        <Table bordered>
                                            <tbody>
                                                <tr>
                                                    <th width="40%">Subkategori</th>
                                                    <td>{selectedEvent.subkategori_lomba.kategori_lomba.nama} - {selectedEvent.subkategori_lomba.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kelompok Usia</th>
                                                    <td>{selectedEvent.kelompok_usia.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <td>{moment(selectedEvent.tanggal).format('dddd, D MMMM YYYY')}</td>
                                                </tr>
                                                <tr>
                                                    <th>Waktu</th>
                                                    <td>{selectedEvent.waktu_mulai} - {selectedEvent.waktu_selesai || 'Selesai'}</td>
                                                </tr>
                                                <tr>
                                                    <th>Lokasi</th>
                                                    <td>{selectedEvent.lokasi_detail || '-'}</td>
                                                </tr>
                                            </tbody>
                                        </Table>
                                    </Col>
                                    <Col md={6}>
                                        <h6>Peserta Terdaftar</h6>
                                        {selectedEvent.pesertas && selectedEvent.pesertas.length > 0 ? (
                                            <Table bordered>
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Kontingen</th>
                                                        <th>Kelas</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {selectedEvent.pesertas.map((peserta, index) => (
                                                        <tr key={index}>
                                                            <td>{peserta.nama}</td>
                                                            <td>{peserta.kontingen.nama}</td>
                                                            <td>{peserta.kelas_tanding?.label_keterangan || '-'}</td>
                                                            <td>
                                                                <Badge bg={
                                                                    peserta.status_verifikasi === 'valid' ? 'success' : 
                                                                    peserta.status_verifikasi === 'pending' ? 'warning' : 'danger'
                                                                }>
                                                                    {peserta.status_verifikasi}
                                                                </Badge>
                                                            </td>
                                                        </tr>
                                                    ))}
                                                </tbody>
                                            </Table>
                                        ) : (
                                            <div className="text-center py-3">
                                                <p className="text-muted mb-0">Belum ada peserta terdaftar</p>
                                            </div>
                                        )}
                                    </Col>
                                </Row>
                            </div>
                            <div className="modal-footer">
                                <Button variant="secondary" onClick={() => setModalVisible(false)}>
                                    Tutup
                                </Button>
                                <a 
                                    href={`/admin/jadwal-pertandingan/${selectedEvent.id}`} 
                                    className="btn btn-info"
                                >
                                    <i className="fas fa-eye me-1"></i> Detail Lengkap
                                </a>
                                <a 
                                    href={`/admin/jadwal-pertandingan/${selectedEvent.id}/edit`} 
                                    className="btn btn-warning"
                                >
                                    <i className="fas fa-edit me-1"></i> Edit Jadwal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            )}
            
            {/* Modal Background Overlay */}
            {modalVisible && (
                <div 
                    className="modal-backdrop fade show" 
                    onClick={() => setModalVisible(false)}
                ></div>
            )}
        </div>
    );
};

export default PertandinganSchedule;