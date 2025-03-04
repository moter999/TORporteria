// Sistema de logging mejorado
const Logger = {
    error: function(context, error) {
        console.error('🔴 ERROR en ' + context + ':', {
            mensaje: error.message || error,
            stack: error.stack,
            timestamp: new Date().toISOString()
        });
    },
    info: function(context, data) {
        console.info('ℹ️ INFO - ' + context + ':', {
            datos: data,
            timestamp: new Date().toISOString()
        });
    },
    warn: function(context, message) {
        console.warn('⚠️ ADVERTENCIA en ' + context + ':', {
            mensaje: message,
            timestamp: new Date().toISOString()
        });
    },
    debug: function(context, data) {
        console.debug('🔍 DEBUG - ' + context + ':', {
            datos: data,
            timestamp: new Date().toISOString()
        });
    }
};

// Funciones de modal
function openCreateModal() {
    try {
        Logger.debug('openCreateModal', 'Iniciando apertura del modal de creación');
        const modal = document.getElementById('createModal');
        if (!modal) {
            throw new Error('Modal de creación no encontrado en el DOM');
        }
        modal.style.display = 'block';
        modal.classList.add('show');
        Logger.info('openCreateModal', 'Modal de creación abierto exitosamente');
    } catch (error) {
        Logger.error('openCreateModal', error);
        alert('Error al abrir el modal de creación');
    }
}

function openEditModal(id) {
    try {
        Logger.debug('openEditModal', { id: id, timestamp: new Date().toISOString() });
        
        if (!id) {
            throw new Error('ID no proporcionado o inválido');
        }

        fetch(`get_chofer.php?id=${id}`)
            .then(response => {
                Logger.debug('openEditModal - Respuesta del servidor', {
                    status: response.status,
                    ok: response.ok,
                    headers: Object.fromEntries(response.headers.entries())
                });
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                Logger.debug('openEditModal - Datos recibidos', data);
                
                if (!data.success) {
                    throw new Error(data.message || 'Error al cargar los datos');
                }

                const modal = document.getElementById('editModal');
                if (!modal) {
                    throw new Error('Modal de edición no encontrado en el DOM');
                }

                // Mapear y validar cada campo
                const campos = {
                    'id': data.data.id,
                    'chofer': data.data.Chofer,
                    'cod1': data.data.cod1,
                    'patente': data.data.Patente,
                    'f_salida': data.data.F_Salida,
                    'f_ingreso': data.data.F_Ingreso,
                    'h_sal': data.data.H_Sal,
                    'h_ing': data.data.H_ing,
                    't_ocupado': data.data.T_Ocupado,
                    'cod2': data.data.Cod2,
                    'lugar': data.data.Lugar,
                    'detalle': data.data.Detalle,
                    'k_ing': data.data.K_Ing,
                    'k_sal': data.data.K_Sal,
                    'k_ocup': data.data.K_Ocup
                };

                Object.entries(campos).forEach(([campo, valor]) => {
                    const elemento = document.getElementById(`edit_${campo}`);
                    if (!elemento) {
                        Logger.warn('openEditModal', `Campo no encontrado: edit_${campo}`);
                        return;
                    }
                    elemento.value = valor || '';
                    Logger.debug('openEditModal - Campo actualizado', { campo, valor });
                });

                modal.style.display = 'block';
                modal.classList.add('show');
                Logger.info('openEditModal', 'Modal de edición abierto exitosamente');
            })
            .catch(error => {
                Logger.error('openEditModal', error);
                alert('Error al cargar los datos: ' + error.message);
            });
    } catch (error) {
        Logger.error('openEditModal', error);
        alert('Error al abrir el modal de edición');
    }
}

function openViewModal(id) {
    try {
        Logger.debug('openViewModal', { id: id, timestamp: new Date().toISOString() });
        
        if (!id) {
            throw new Error('ID no proporcionado o inválido');
        }

        fetch(`get_chofer.php?id=${id}`)
            .then(response => {
                Logger.debug('openViewModal - Respuesta del servidor', {
                    status: response.status,
                    ok: response.ok,
                    headers: Object.fromEntries(response.headers.entries())
                });
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                Logger.debug('openViewModal - Datos recibidos', data);
                
                if (!data.success) {
                    throw new Error(data.message || 'Error al cargar los datos');
                }

                const modal = document.getElementById('viewModal');
                const viewContent = document.getElementById('viewContent');
                
                if (!modal || !viewContent) {
                    throw new Error('Modal de vista o contenido no encontrado en el DOM');
                }

                // Crear contenido HTML con validación de datos
                const campos = [
                    {label: 'ID', valor: data.data.id},
                    {label: 'Chofer', valor: data.data.Chofer},
                    {label: 'Cod1', valor: data.data.cod1},
                    {label: 'Patente', valor: data.data.Patente},
                    {label: 'F. Salida', valor: data.data.F_Salida},
                    {label: 'F. Ingreso', valor: data.data.F_Ingreso},
                    {label: 'H. Salida', valor: data.data.H_Sal},
                    {label: 'H. Ingreso', valor: data.data.H_ing},
                    {label: 'T. Ocupado', valor: data.data.T_Ocupado},
                    {label: 'Cod2', valor: data.data.Cod2},
                    {label: 'Lugar', valor: data.data.Lugar},
                    {label: 'Detalle', valor: data.data.Detalle},
                    {label: 'K. Ingreso', valor: data.data.K_Ing},
                    {label: 'K. Salida', valor: data.data.K_Sal},
                    {label: 'K. Ocupado', valor: data.data.K_Ocup}
                ];

                viewContent.innerHTML = campos
                    .map(campo => `<p><strong>${campo.label}:</strong> ${campo.valor || '-'}</p>`)
                    .join('');

                modal.style.display = 'block';
                modal.classList.add('show');
                Logger.info('openViewModal', 'Modal de vista abierto exitosamente');
            })
            .catch(error => {
                Logger.error('openViewModal', error);
                alert('Error al cargar los datos: ' + error.message);
            });
    } catch (error) {
        Logger.error('openViewModal', error);
        alert('Error al abrir el modal de vista');
    }
}

function closeCreateModal() {
    try {
        const modal = document.getElementById('createModal');
        if (!modal) {
            throw new Error('Modal de creación no encontrado');
        }
        modal.style.display = 'none';
        modal.classList.remove('show');
        Logger.info('closeCreateModal', 'Modal de creación cerrado exitosamente');
    } catch (error) {
        Logger.error('closeCreateModal', error);
    }
}

function closeEditModal() {
    try {
        const modal = document.getElementById('editModal');
        if (!modal) {
            throw new Error('Modal de edición no encontrado');
        }
        modal.style.display = 'none';
        modal.classList.remove('show');
        Logger.info('closeEditModal', 'Modal de edición cerrado exitosamente');
    } catch (error) {
        Logger.error('closeEditModal', error);
    }
}

function closeViewModal() {
    try {
        const modal = document.getElementById('viewModal');
        if (!modal) {
            throw new Error('Modal de vista no encontrado');
        }
        modal.style.display = 'none';
        modal.classList.remove('show');
        Logger.info('closeViewModal', 'Modal de vista cerrado exitosamente');
    } catch (error) {
        Logger.error('closeViewModal', error);
    }
}

function calculateTOcupado(modalType) {
    try {
        Logger.debug('calculateTOcupado', { modalType, timestamp: new Date().toISOString() });
        
        const hSal = document.getElementById(`${modalType}_h_sal`).value;
        const hIng = document.getElementById(`${modalType}_h_ing`).value;
        
        if (!hSal || !hIng) {
            Logger.warn('calculateTOcupado', 'Valores de hora incompletos');
            return;
        }

        const [hSalHour, hSalMinute] = hSal.split(':').map(Number);
        const [hIngHour, hIngMinute] = hIng.split(':').map(Number);
        
        if (isNaN(hSalHour) || isNaN(hSalMinute) || isNaN(hIngHour) || isNaN(hIngMinute)) {
            throw new Error(`Formato de hora inválido - H.Sal: ${hSal}, H.Ing: ${hIng}`);
        }

        let diffHours = hIngHour - hSalHour;
        let diffMinutes = hIngMinute - hSalMinute;

        if (diffMinutes < 0) {
            diffHours--;
            diffMinutes += 60;
        }

        if (diffHours < 0) {
            diffHours += 24;
        }

        const resultado = `${String(diffHours).padStart(2, '0')}:${String(diffMinutes).padStart(2, '0')}`;
        document.getElementById(`${modalType}_t_ocupado`).value = resultado;
        Logger.debug('calculateTOcupado', { 
            entrada: { hSal, hIng },
            calculo: { diffHours, diffMinutes },
            resultado
        });
    } catch (error) {
        Logger.error('calculateTOcupado', error);
        alert('Error al calcular el tiempo ocupado: ' + error.message);
    }
}

function autoFillCod1(modalType) {
    try {
        const chofer = document.getElementById(`${modalType}_chofer`).value;
        Logger.debug('autoFillCod1', { modalType, chofer });
        
        if (!chofer) {
            Logger.warn('autoFillCod1', 'Nombre de chofer no proporcionado');
            return;
        }

        fetch('get_cod1.php?chofer=' + encodeURIComponent(chofer))
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                Logger.debug('autoFillCod1 - Respuesta', data);
                if (data.cod1) {
                    document.getElementById(`${modalType}_cod1`).value = data.cod1;
                    Logger.info('autoFillCod1', `Código asignado: ${data.cod1}`);
                }
            })
            .catch(error => {
                Logger.error('autoFillCod1', error);
            });
    } catch (error) {
        Logger.error('autoFillCod1', error);
    }
}

// Asegurar que las funciones estén disponibles globalmente
window.openCreateModal = openCreateModal;
window.openEditModal = openEditModal;
window.openViewModal = openViewModal;
window.closeCreateModal = closeCreateModal;
window.closeEditModal = closeEditModal;
window.closeViewModal = closeViewModal;
window.calculateTOcupado = calculateTOcupado;
window.autoFillCod1 = autoFillCod1; 