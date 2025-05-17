<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý phân quyền</h1>
      </div>
  
      <!-- Tabs -->
      <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8" aria-label="Tabs">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap"
            :class="[
              activeTab === tab.id
                ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-500'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-300'
            ]"
          >
            {{ tab.name }}
          </button>
        </nav>
      </div>
  
      <!-- Chức vụ Tab -->
      <div v-if="activeTab === 'roles'" class="space-y-6">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-2">
            <div class="relative">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                type="text" 
                placeholder="Tìm kiếm chức vụ..." 
                class="pl-9 h-10 w-full sm:w-[250px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="roleSearch"
              />
            </div>
            <select 
              v-model="roleStatusFilter" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="all">Tất cả trạng thái</option>
              <option value="active">Đang hoạt động</option>
              <option value="inactive">Không hoạt động</option>
            </select>
          </div>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
            @click="showAddRoleModal = true"
          >
            <Plus class="mr-2 h-4 w-4" />
            Thêm chức vụ
          </button>
        </div>
  
        <!-- Danh sách chức vụ -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tên chức vụ</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="role in filteredRoles" :key="role.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    {{ role.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ role.ten_chuc_vu }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="role.tinh_trang ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'"
                    >
                      {{ role.tinh_trang ? 'Đang hoạt động' : 'Không hoạt động' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <button 
                        class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                        @click="editRole(role)"
                      >
                        <Edit class="h-4 w-4" />
                      </button>
                      <button 
                        class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                        @click="manageRolePermissions(role)"
                      >
                        <Shield class="h-4 w-4" />
                      </button>
                      <button 
                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400"
                        @click="deleteRole(role)"
                      >
                        <Trash2 class="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredRoles.length === 0">
                  <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                    Không tìm thấy chức vụ nào
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
      <!-- Chức năng Tab -->
      <div v-if="activeTab === 'permissions'" class="space-y-6">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-2">
            <div class="relative">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                type="text" 
                placeholder="Tìm kiếm chức năng..." 
                class="pl-9 h-10 w-full sm:w-[250px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="permissionSearch"
              />
            </div>
            <select 
              v-model="permissionStatusFilter" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="all">Tất cả trạng thái</option>
              <option value="active">Đang hoạt động</option>
              <option value="inactive">Không hoạt động</option>
            </select>
          </div>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
            @click="showAddPermissionModal = true"
          >
            <Plus class="mr-2 h-4 w-4" />
            Thêm chức năng
          </button>
        </div>
  
        <!-- Danh sách chức năng -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tên chức năng</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="permission in filteredPermissions" :key="permission.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    {{ permission.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ permission.ten_chuc_nang }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="permission.tinh_trang ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'"
                    >
                      {{ permission.tinh_trang ? 'Đang hoạt động' : 'Không hoạt động' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <button 
                        class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                        @click="editPermission(permission)"
                      >
                        <Edit class="h-4 w-4" />
                      </button>
                      <button 
                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400"
                        @click="deletePermission(permission)"
                      >
                        <Trash2 class="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredPermissions.length === 0">
                  <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                    Không tìm thấy chức năng nào
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
      <!-- Phân quyền Tab -->
      <div v-if="activeTab === 'role-permissions'" class="space-y-6">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-2">
            <select 
              v-model="selectedRoleId" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Chọn chức vụ</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.ten_chuc_vu }}
              </option>
            </select>
          </div>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            :disabled="!selectedRoleId"
            :class="{'opacity-50 cursor-not-allowed': !selectedRoleId}"
            @click="saveRolePermissions"
          >
            Lưu phân quyền
          </button>
        </div>
  
        <!-- Bảng phân quyền -->
        <div v-if="selectedRoleId" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4">
            <div class="mb-4 flex items-center justify-between">
              <h3 class="text-lg font-medium">Phân quyền cho: {{ selectedRoleName }}</h3>
              <div class="flex items-center space-x-4">
                <button 
                  class="text-sm text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                  @click="selectAllPermissions"
                >
                  Chọn tất cả
                </button>
                <button 
                  class="text-sm text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                  @click="deselectAllPermissions"
                >
                  Bỏ chọn tất cả
                </button>
              </div>
            </div>
            
            <div class="space-y-4">
              <div class="relative">
                <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
                <input 
                  type="text" 
                  placeholder="Tìm kiếm chức năng..." 
                  class="pl-9 h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 mb-4"
                  v-model="rolePermissionSearch"
                />
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div 
                  v-for="permission in filteredRolePermissions" 
                  :key="permission.id"
                  class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
                >
                  <label class="flex items-center space-x-2">
                    <input 
                      type="checkbox" 
                      :value="permission.id" 
                      v-model="selectedPermissions"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                    />
                    <span>{{ permission.ten_chuc_nang }}</span>
                  </label>
                  <p class="text-xs text-gray-500 mt-1 ml-6">ID: {{ permission.id }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div v-else class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-8 text-center">
          <Shield class="h-12 w-12 mx-auto text-gray-400 mb-4" />
          <p class="text-gray-500">Vui lòng chọn một chức vụ để phân quyền</p>
        </div>
      </div>
  
      <!-- Nhân sự Tab -->
      <div v-if="activeTab === 'personnel'" class="space-y-6">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-2">
            <div class="relative">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                type="text" 
                placeholder="Tìm kiếm nhân sự..." 
                class="pl-9 h-10 w-full sm:w-[250px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="personnelSearch"
              />
            </div>
            <select 
              v-model="personnelStatusFilter" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="all">Tất cả trạng thái</option>
              <option value="active">Đang làm việc</option>
              <option value="inactive">Đã nghỉ việc</option>
            </select>
            <select 
              v-model="personnelRoleFilter" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="all">Tất cả chức vụ</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.ten_chuc_vu }}
              </option>
            </select>
          </div>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
            @click="showAddPersonnelModal = true"
          >
            <Plus class="mr-2 h-4 w-4" />
            Thêm nhân sự
          </button>
        </div>

        <!-- Danh sách nhân sự -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Họ và tên</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chức vụ</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số điện thoại</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="person in filteredPersonnel" :key="person.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    {{ person.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ person.ho_ten }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ getRoleName(person.id_chuc_vu) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ person.email }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ person.so_dien_thoai }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="person.tinh_trang ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'"
                    >
                      {{ person.tinh_trang ? 'Đang làm việc' : 'Đã nghỉ việc' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <button 
                        class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                        @click="editPersonnel(person)"
                      >
                        <Edit class="h-4 w-4" />
                      </button>
                      <button 
                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400"
                        @click="deletePersonnel(person)"
                      >
                        <Trash2 class="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredPersonnel.length === 0">
                  <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                    Không tìm thấy nhân sự nào
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
      <!-- Modal thêm chức vụ -->
      <div v-if="showAddRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">{{ editingRole ? 'Chỉnh sửa chức vụ' : 'Thêm chức vụ mới' }}</h3>
            <button @click="closeRoleModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="saveRole">
              <div class="space-y-4">
                <div>
                  <label for="roleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên chức vụ *</label>
                  <input 
                    id="roleName" 
                    type="text" 
                    v-model="roleForm.ten_chuc_vu"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập tên chức vụ"
                  />
                </div>
                
                <div>
                  <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <input 
                      type="checkbox" 
                      v-model="roleForm.tinh_trang"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600"
                    />
                    <span>Đang hoạt động</span>
                  </label>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="closeRoleModal"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    {{ editingRole ? 'Cập nhật' : 'Thêm' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- Modal thêm chức năng -->
      <div v-if="showAddPermissionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">{{ editingPermission ? 'Chỉnh sửa chức năng' : 'Thêm chức năng mới' }}</h3>
            <button @click="closePermissionModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="savePermission">
              <div class="space-y-4">
                <div>
                  <label for="permissionName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên chức năng *</label>
                  <input 
                    id="permissionName" 
                    type="text" 
                    v-model="permissionForm.ten_chuc_nang"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập tên chức năng"
                  />
                </div>
                
                <div>
                  <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <input 
                      type="checkbox" 
                      v-model="permissionForm.tinh_trang"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600"
                    />
                    <span>Đang hoạt động</span>
                  </label>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="closePermissionModal"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    {{ editingPermission ? 'Cập nhật' : 'Thêm' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- Modal xác nhận xóa -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Xác nhận xóa</h3>
            <button @click="showDeleteModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <p class="mb-4">{{ deleteMessage }}</p>
            <div class="flex justify-end space-x-2">
              <button 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                @click="showDeleteModal = false"
              >
                Hủy
              </button>
              <button 
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                @click="confirmDelete"
              >
                Xóa
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal thêm/sửa nhân sự -->
      <div v-if="showAddPersonnelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">{{ editingPersonnel ? 'Chỉnh sửa nhân sự' : 'Thêm nhân sự mới' }}</h3>
            <button @click="closePersonnelModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="savePersonnel">
              <div class="space-y-4">
                <div>
                  <label for="personnelName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ và tên *</label>
                  <input 
                    id="personnelName" 
                    type="text" 
                    v-model="personnelForm.ho_ten"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập họ và tên"
                  />
                </div>
                
                <div>
                  <label for="personnelEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                  <input 
                    id="personnelEmail" 
                    type="email" 
                    v-model="personnelForm.email"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập email"
                  />
                </div>

                <div>
                  <label for="personnelPhone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại *</label>
                  <input 
                    id="personnelPhone" 
                    type="tel" 
                    v-model="personnelForm.so_dien_thoai"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập số điện thoại"
                  />
                </div>

                <div>
                  <label for="personnelRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chức vụ *</label>
                  <select 
                    id="personnelRole"
                    v-model="personnelForm.id_chuc_vu"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="">Chọn chức vụ</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">
                      {{ role.ten_chuc_vu }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <input 
                      type="checkbox" 
                      v-model="personnelForm.tinh_trang"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600"
                    />
                    <span>Đang làm việc</span>
                  </label>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="closePersonnelModal"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    {{ editingPersonnel ? 'Cập nhật' : 'Thêm' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed, onMounted } from 'vue'
  import { 
    Plus, 
    Search, 
    Edit, 
    Trash2, 
    Shield, 
    X, 
    Check 
  } from 'lucide-vue-next'
  
  export default {
    name: 'PhanQuyen',
    components: {
      Plus,
      Search,
      Edit,
      Trash2,
      Shield,
      X,
      Check
    },
    setup() {
      // Tabs
      const tabs = [
        { id: 'roles', name: 'Chức vụ' },
        { id: 'permissions', name: 'Chức năng' },
        { id: 'role-permissions', name: 'Phân quyền' },
        { id: 'personnel', name: 'Nhân sự' }
      ]
      const activeTab = ref('roles')
  
      // Dữ liệu
      const roles = ref([])
      const permissions = ref([])
      const rolePermissions = ref([])
  
      // Tìm kiếm và lọc
      const roleSearch = ref('')
      const roleStatusFilter = ref('all')
      const permissionSearch = ref('')
      const permissionStatusFilter = ref('all')
      const rolePermissionSearch = ref('')
  
      // Modals
      const showAddRoleModal = ref(false)
      const showAddPermissionModal = ref(false)
      const showDeleteModal = ref(false)
      const deleteMessage = ref('')
      const deleteCallback = ref(null)
  
      // Forms
      const roleForm = ref({
        ten_chuc_vu: '',
        tinh_trang: true
      })
      const permissionForm = ref({
        ten_chuc_nang: '',
        tinh_trang: true
      })
      const editingRole = ref(null)
      const editingPermission = ref(null)
  
      // Phân quyền
      const selectedRoleId = ref('')
      const selectedPermissions = ref([])
  
      // Dữ liệu nhân sự
      const personnel = ref([])
      const personnelSearch = ref('')
      const personnelStatusFilter = ref('all')
      const personnelRoleFilter = ref('all')
      const showAddPersonnelModal = ref(false)
      const editingPersonnel = ref(null)
      const personnelForm = ref({
        ho_ten: '',
        email: '',
        so_dien_thoai: '',
        id_chuc_vu: '',
        tinh_trang: true
      })
  
      // Lấy dữ liệu khi component được tạo
      onMounted(() => {
        fetchData()
      })
  
      // Lấy dữ liệu từ server
      const fetchData = async () => {
        try {
          // Trong thực tế, bạn sẽ gọi API để lấy dữ liệu
          // Ở đây chúng ta sẽ sử dụng dữ liệu mẫu
          roles.value = [
            { id: 1, ten_chuc_vu: 'Admin', tinh_trang: true },
            { id: 2, ten_chuc_vu: 'Quản lý', tinh_trang: true },
            { id: 3, ten_chuc_vu: 'Nhân viên', tinh_trang: true },
            { id: 4, ten_chuc_vu: 'Khách hàng', tinh_trang: false }
          ]
          
          permissions.value = [
            { id: 1, ten_chuc_nang: 'Quản lý người dùng', tinh_trang: true },
            { id: 2, ten_chuc_nang: 'Quản lý bãi đỗ xe', tinh_trang: true },
            { id: 3, ten_chuc_nang: 'Quản lý khách vãng lai', tinh_trang: true },
            { id: 4, ten_chuc_nang: 'Quản lý cư dân', tinh_trang: true },
            { id: 5, ten_chuc_nang: 'Quản lý thanh toán', tinh_trang: true },
            { id: 6, ten_chuc_nang: 'Quản lý báo cáo', tinh_trang: true },
            { id: 7, ten_chuc_nang: 'Quản lý phân quyền', tinh_trang: true },
            { id: 8, ten_chuc_nang: 'Xem thống kê', tinh_trang: true },
            { id: 9, ten_chuc_nang: 'Xuất báo cáo', tinh_trang: false }
          ]
          
          rolePermissions.value = [
            { id: 1, id_chuc_vu: 1, id_chuc_nang: 1, tinh_trang: true },
            { id: 2, id_chuc_vu: 1, id_chuc_nang: 2, tinh_trang: true },
            { id: 3, id_chuc_vu: 1, id_chuc_nang: 3, tinh_trang: true },
            { id: 4, id_chuc_vu: 1, id_chuc_nang: 4, tinh_trang: true },
            { id: 5, id_chuc_vu: 1, id_chuc_nang: 5, tinh_trang: true },
            { id: 6, id_chuc_vu: 1, id_chuc_nang: 6, tinh_trang: true },
            { id: 7, id_chuc_vu: 1, id_chuc_nang: 7, tinh_trang: true },
            { id: 8, id_chuc_vu: 1, id_chuc_nang: 8, tinh_trang: true },
            { id: 9, id_chuc_vu: 1, id_chuc_nang: 9, tinh_trang: true },
            { id: 10, id_chuc_vu: 2, id_chuc_nang: 2, tinh_trang: true },
            { id: 11, id_chuc_vu: 2, id_chuc_nang: 3, tinh_trang: true },
            { id: 12, id_chuc_vu: 2, id_chuc_nang: 4, tinh_trang: true },
            { id: 13, id_chuc_vu: 2, id_chuc_nang: 5, tinh_trang: true },
            { id: 14, id_chuc_vu: 2, id_chuc_nang: 6, tinh_trang: true },
            { id: 15, id_chuc_vu: 2, id_chuc_nang: 8, tinh_trang: true },
            { id: 16, id_chuc_vu: 3, id_chuc_nang: 3, tinh_trang: true },
            { id: 17, id_chuc_vu: 3, id_chuc_nang: 4, tinh_trang: true },
            { id: 18, id_chuc_vu: 3, id_chuc_nang: 8, tinh_trang: true }
          ]
          
          personnel.value = [
            { 
              id: 1, 
              ho_ten: 'Nguyễn Văn A', 
              email: 'nguyenvana@example.com',
              so_dien_thoai: '0123456789',
              id_chuc_vu: 1,
              tinh_trang: true 
            },
            { 
              id: 2, 
              ho_ten: 'Trần Thị B', 
              email: 'tranthib@example.com',
              so_dien_thoai: '0987654321',
              id_chuc_vu: 2,
              tinh_trang: true 
            },
            { 
              id: 3, 
              ho_ten: 'Lê Văn C', 
              email: 'levanc@example.com',
              so_dien_thoai: '0369852147',
              id_chuc_vu: 3,
              tinh_trang: false 
            }
          ]
        } catch (error) {
          console.error('Lỗi khi lấy dữ liệu:', error)
        }
      }
  
      // Lọc chức vụ
      const filteredRoles = computed(() => {
        let result = [...roles.value]
        
        // Lọc theo tìm kiếm
        if (roleSearch.value) {
          const searchLower = roleSearch.value.toLowerCase()
          result = result.filter(role => 
            role.ten_chuc_vu.toLowerCase().includes(searchLower)
          )
        }
        
        // Lọc theo trạng thái
        if (roleStatusFilter.value !== 'all') {
          const isActive = roleStatusFilter.value === 'active'
          result = result.filter(role => role.tinh_trang === isActive)
        }
        
        return result
      })
  
      // Lọc chức năng
      const filteredPermissions = computed(() => {
        let result = [...permissions.value]
        
        // Lọc theo tìm kiếm
        if (permissionSearch.value) {
          const searchLower = permissionSearch.value.toLowerCase()
          result = result.filter(permission => 
            permission.ten_chuc_nang.toLowerCase().includes(searchLower)
          )
        }
        
        // Lọc theo trạng thái
        if (permissionStatusFilter.value !== 'all') {
          const isActive = permissionStatusFilter.value === 'active'
          result = result.filter(permission => permission.tinh_trang === isActive)
        }
        
        return result
      })
  
      // Lọc chức năng cho phân quyền
      const filteredRolePermissions = computed(() => {
        let result = [...permissions.value]
        
        // Lọc theo tìm kiếm
        if (rolePermissionSearch.value) {
          const searchLower = rolePermissionSearch.value.toLowerCase()
          result = result.filter(permission => 
            permission.ten_chuc_nang.toLowerCase().includes(searchLower)
          )
        }
        
        return result
      })
  
      // Lấy tên chức vụ đã chọn
      const selectedRoleName = computed(() => {
        const role = roles.value.find(r => r.id === selectedRoleId.value)
        return role ? role.ten_chuc_vu : ''
      })
  
      // Thêm/Sửa chức vụ
      const editRole = (role) => {
        editingRole.value = role
        roleForm.value = {
          ten_chuc_vu: role.ten_chuc_vu,
          tinh_trang: role.tinh_trang
        }
        showAddRoleModal.value = true
      }
  
      const closeRoleModal = () => {
        showAddRoleModal.value = false
        editingRole.value = null
        roleForm.value = {
          ten_chuc_vu: '',
          tinh_trang: true
        }
      }
  
      const saveRole = () => {
        if (editingRole.value) {
          // Cập nhật chức vụ
          const index = roles.value.findIndex(r => r.id === editingRole.value.id)
          if (index !== -1) {
            roles.value[index] = {
              ...roles.value[index],
              ten_chuc_vu: roleForm.value.ten_chuc_vu,
              tinh_trang: roleForm.value.tinh_trang
            }
          }
        } else {
          // Thêm chức vụ mới
          const newId = Math.max(0, ...roles.value.map(r => r.id)) + 1
          roles.value.push({
            id: newId,
            ten_chuc_vu: roleForm.value.ten_chuc_vu,
            tinh_trang: roleForm.value.tinh_trang
          })
        }
        
        closeRoleModal()
      }
  
      // Thêm/Sửa chức năng
      const editPermission = (permission) => {
        editingPermission.value = permission
        permissionForm.value = {
          ten_chuc_nang: permission.ten_chuc_nang,
          tinh_trang: permission.tinh_trang
        }
        showAddPermissionModal.value = true
      }
  
      const closePermissionModal = () => {
        showAddPermissionModal.value = false
        editingPermission.value = null
        permissionForm.value = {
          ten_chuc_nang: '',
          tinh_trang: true
        }
      }
  
      const savePermission = () => {
        if (editingPermission.value) {
          // Cập nhật chức năng
          const index = permissions.value.findIndex(p => p.id === editingPermission.value.id)
          if (index !== -1) {
            permissions.value[index] = {
              ...permissions.value[index],
              ten_chuc_nang: permissionForm.value.ten_chuc_nang,
              tinh_trang: permissionForm.value.tinh_trang
            }
          }
        } else {
          // Thêm chức năng mới
          const newId = Math.max(0, ...permissions.value.map(p => p.id)) + 1
          permissions.value.push({
            id: newId,
            ten_chuc_nang: permissionForm.value.ten_chuc_nang,
            tinh_trang: permissionForm.value.tinh_trang
          })
        }
        
        closePermissionModal()
      }
  
      // Xóa chức vụ
      const deleteRole = (role) => {
        deleteMessage.value = `Bạn có chắc chắn muốn xóa chức vụ "${role.ten_chuc_vu}" không?`
        deleteCallback.value = () => {
          roles.value = roles.value.filter(r => r.id !== role.id)
          // Xóa các phân quyền liên quan
          rolePermissions.value = rolePermissions.value.filter(rp => rp.id_chuc_vu !== role.id)
        }
        showDeleteModal.value = true
      }
  
      // Xóa chức năng
      const deletePermission = (permission) => {
        deleteMessage.value = `Bạn có chắc chắn muốn xóa chức năng "${permission.ten_chuc_nang}" không?`
        deleteCallback.value = () => {
          permissions.value = permissions.value.filter(p => p.id !== permission.id)
          // Xóa các phân quyền liên quan
          rolePermissions.value = rolePermissions.value.filter(rp => rp.id_chuc_nang !== permission.id)
        }
        showDeleteModal.value = true
      }
  
      // Xác nhận xóa
      const confirmDelete = () => {
        if (deleteCallback.value) {
          deleteCallback.value()
        }
        showDeleteModal.value = false
        deleteCallback.value = null
      }
  
      // Quản lý phân quyền cho chức vụ
      const manageRolePermissions = (role) => {
        selectedRoleId.value = role.id
        loadRolePermissions()
        activeTab.value = 'role-permissions'
      }
  
      // Tải danh sách quyền của chức vụ
      const loadRolePermissions = () => {
        const rolePerms = rolePermissions.value.filter(rp => 
          rp.id_chuc_vu === selectedRoleId.value && rp.tinh_trang
        )
        selectedPermissions.value = rolePerms.map(rp => rp.id_chuc_nang)
      }
  
      // Chọn tất cả quyền
      const selectAllPermissions = () => {
        selectedPermissions.value = permissions.value
          .filter(p => p.tinh_trang)
          .map(p => p.id)
      }
  
      // Bỏ chọn tất cả quyền
      const deselectAllPermissions = () => {
        selectedPermissions.value = []
      }
  
      // Lưu phân quyền
      const saveRolePermissions = () => {
        // Xóa tất cả quyền hiện tại của chức vụ
        rolePermissions.value = rolePermissions.value.filter(rp => rp.id_chuc_vu !== selectedRoleId.value)
        
        // Thêm các quyền mới
        let maxId = Math.max(0, ...rolePermissions.value.map(rp => rp.id))
        
        for (const permissionId of selectedPermissions.value) {
          maxId++
          rolePermissions.value.push({
            id: maxId,
            id_chuc_vu: selectedRoleId.value,
            id_chuc_nang: permissionId,
            tinh_trang: true
          })
        }
        
        // Hiển thị thông báo thành công (trong thực tế sẽ sử dụng toast hoặc alert)
        alert('Lưu phân quyền thành công!')
      }
  
      // Theo dõi thay đổi của selectedRoleId
      const watchSelectedRoleId = (newValue) => {
        if (newValue) {
          loadRolePermissions()
        } else {
          selectedPermissions.value = []
        }
      }
  
      // Lọc nhân sự
      const filteredPersonnel = computed(() => {
        let result = [...personnel.value]
        
        // Lọc theo tìm kiếm
        if (personnelSearch.value) {
          const searchLower = personnelSearch.value.toLowerCase()
          result = result.filter(person => 
            person.ho_ten.toLowerCase().includes(searchLower) ||
            person.email.toLowerCase().includes(searchLower) ||
            person.so_dien_thoai.includes(searchLower)
          )
        }
        
        // Lọc theo trạng thái
        if (personnelStatusFilter.value !== 'all') {
          const isActive = personnelStatusFilter.value === 'active'
          result = result.filter(person => person.tinh_trang === isActive)
        }

        // Lọc theo chức vụ
        if (personnelRoleFilter.value !== 'all') {
          result = result.filter(person => person.id_chuc_vu === personnelRoleFilter.value)
        }
        
        return result
      })
  
      // Lấy tên chức vụ
      const getRoleName = (roleId) => {
        const role = roles.value.find(r => r.id === roleId)
        return role ? role.ten_chuc_vu : ''
      }
  
      // Thêm/Sửa nhân sự
      const editPersonnel = (person) => {
        editingPersonnel.value = person
        personnelForm.value = {
          ho_ten: person.ho_ten,
          email: person.email,
          so_dien_thoai: person.so_dien_thoai,
          id_chuc_vu: person.id_chuc_vu,
          tinh_trang: person.tinh_trang
        }
        showAddPersonnelModal.value = true
      }
  
      const closePersonnelModal = () => {
        showAddPersonnelModal.value = false
        editingPersonnel.value = null
        personnelForm.value = {
          ho_ten: '',
          email: '',
          so_dien_thoai: '',
          id_chuc_vu: '',
          tinh_trang: true
        }
      }
  
      const savePersonnel = () => {
        if (editingPersonnel.value) {
          // Cập nhật nhân sự
          const index = personnel.value.findIndex(p => p.id === editingPersonnel.value.id)
          if (index !== -1) {
            personnel.value[index] = {
              ...personnel.value[index],
              ...personnelForm.value
            }
          }
        } else {
          // Thêm nhân sự mới
          const newId = Math.max(0, ...personnel.value.map(p => p.id)) + 1
          personnel.value.push({
            id: newId,
            ...personnelForm.value
          })
        }
        
        closePersonnelModal()
      }
  
      // Xóa nhân sự
      const deletePersonnel = (person) => {
        deleteMessage.value = `Bạn có chắc chắn muốn xóa nhân sự "${person.ho_ten}" không?`
        deleteCallback.value = () => {
          personnel.value = personnel.value.filter(p => p.id !== person.id)
        }
        showDeleteModal.value = true
      }
  
      return {
        tabs,
        activeTab,
        roles,
        permissions,
        rolePermissions,
        roleSearch,
        roleStatusFilter,
        permissionSearch,
        permissionStatusFilter,
        rolePermissionSearch,
        showAddRoleModal,
        showAddPermissionModal,
        showDeleteModal,
        deleteMessage,
        roleForm,
        permissionForm,
        editingRole,
        editingPermission,
        selectedRoleId,
        selectedPermissions,
        filteredRoles,
        filteredPermissions,
        filteredRolePermissions,
        selectedRoleName,
        editRole,
        closeRoleModal,
        saveRole,
        editPermission,
        closePermissionModal,
        savePermission,
        deleteRole,
        deletePermission,
        confirmDelete,
        manageRolePermissions,
        loadRolePermissions,
        selectAllPermissions,
        deselectAllPermissions,
        saveRolePermissions,
        watchSelectedRoleId,
        personnel,
        personnelSearch,
        personnelStatusFilter,
        personnelRoleFilter,
        showAddPersonnelModal,
        editingPersonnel,
        personnelForm,
        filteredPersonnel,
        getRoleName,
        editPersonnel,
        closePersonnelModal,
        savePersonnel,
        deletePersonnel
      }
    }
  }
  </script>