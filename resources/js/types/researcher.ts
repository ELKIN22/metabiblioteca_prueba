export interface Researcher {
  id: number
  orcid: string
  given_names: string
  family_name: string
  primary_email: string | null
  keywords: string[]
  created_at: string
  updated_at: string
}

export interface Pagination {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
  prev: string | null
  next: string | null
}

export interface PaginatedResponse<T> {
  data: T[]
  pagination: Pagination
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
}

export interface ApiResponse<T> {
  success: boolean
  message?: string
  data?: T
}

export interface CreateResearcherRequest {
  orcid: string
}

export interface UpdateResearcherRequest {
  orcid: string
  given_names?: string
  family_name?: string
  primary_email?: string
  keywords?: string[]
}
