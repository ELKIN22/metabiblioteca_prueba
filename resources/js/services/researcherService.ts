import axios from 'axios'
import type {
  Researcher,
  PaginatedResponse,
  ApiResponse,
  CreateResearcherRequest,
  UpdateResearcherRequest
} from '@/types/researcher'

const api = axios.create({
  baseURL: '/api/orcid',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

export const researcherService = {
  async getAll(page = 1): Promise<ApiResponse<PaginatedResponse<Researcher>>> {
    try {
      const response = await api.get(`/list?page=${page}`)
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error al obtener investigadores')
    }
  },

  async getById(orcid: string): Promise<ApiResponse<Researcher>> {
    try {
      const response = await api.get(`/${orcid}`)
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error al obtener investigador')
    }
  },

  async create(data: CreateResearcherRequest): Promise<ApiResponse<void>> {
    try {
      const response = await api.post('/create', data)
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error al crear investigador')
    }
  },

  async update(data: UpdateResearcherRequest): Promise<ApiResponse<void>> {
    try {
      const response = await api.put('/update', data)
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error al actualizar investigador')
    }
  },

  async delete(orcid: string): Promise<ApiResponse<void>> {
    try {
      const response = await api.delete(`/delete/${orcid}`)
      return response.data
    } catch (error: any) {
      throw new Error(error.response?.data?.message || 'Error al eliminar investigador')
    }
  }
}
