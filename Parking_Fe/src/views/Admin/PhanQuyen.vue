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
              <a-input 
                type="text" 
                placeholder="Tìm kiếm chức vụ..." 
                class="pl-9 h-10 w-full sm:w-[250px]"
                v-model:value="roleSearch"
              />
            </div>
            <a-select 
              v-model:value="roleStatusFilter" 
              class="h-10 w-[200px]"
            >
              <a-select-option value="all">Tất cả trạng thái</a-select-option>
              <a-select-option value="active">Đang hoạt động</a-select-option>
              <a-select-option value="inactive">Không hoạt động</a-select-option>
            </a-select>
          </div>
          <a-button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
            type="primary"
            @click="showAddRoleModal = true"
          >
            <Plus class="mr-2 h-4 w-4" />
            Thêm chức vụ
          </a-button>
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
                      <a-tooltip title="Chỉnh sửa">
                        <a-button 
                          type="primary"
                          size="small"
                          @click="editRole(role)"
                        >
                          <Edit class="h-4 w-4" />
                        </a-button>
                      </a-tooltip>
                      <a-tooltip title="Phân quyền">
                        <a-button 
                          type="primary"
                          size="small"
                          @click="manageRolePermissions(role)"
                        >
                          <Shield class="h-4 w-4" />
                        </a-button>
                      </a-tooltip>
                      <a-tooltip title="Xóa">
                        <a-popconfirm
                          title="Bạn có chắc chắn muốn xóa chức vụ này không?"
                          ok-text="Có"
                          cancel-text="Không"
                          @confirm="deleteRole(role)"
                        >
                          <a-button 
                            type="primary"
                            danger
                            size="small"
                          >
                            <Trash2 class="h-4 w-4" />
                          </a-button>
                        </a-popconfirm>
                      </a-tooltip>
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
            <a-select 
              v-model:value="selectedRoleId" 
              class="h-10 w-[200px]"
            >
              <a-select-option value="">Chọn chức vụ</a-select-option>
              <a-select-option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.ten_chuc_vu }}
              </a-select-option>
            </a-select>
          </div>
          <a-popconfirm
            title="Xác nhận lưu phân quyền"
            description="Bạn có chắc chắn muốn lưu phân quyền cho chức vụ này không?"
            ok-text="Có"
            cancel-text="Không"
            @confirm="saveRolePermissions"
          >
            <a-button 
              type="primary"
              :disabled="!selectedRoleId"
              :class="{'opacity-50 cursor-not-allowed': !selectedRoleId}"
            >
              Lưu phân quyền
            </a-button>
          </a-popconfirm>
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
              <a-input 
                type="text" 
                placeholder="Tìm kiếm nhân sự..." 
                class="pl-9 h-10 w-full sm:w-[250px]"
                v-model:value="personnelSearch"
              />
            </div>
            <a-select 
              v-model:value="personnelStatusFilter" 
              class="h-10 w-[200px]"
            >
              <a-select-option value="all">Tất cả trạng thái</a-select-option>
              <a-select-option value="active">Đang làm việc</a-select-option>
              <a-select-option value="inactive">Đã nghỉ việc</a-select-option>
            </a-select>
            <a-select 
              v-model:value="personnelRoleFilter" 
              class="h-10 w-[200px]"
            >
              <a-select-option value="all">Tất cả chức vụ</a-select-option>
              <a-select-option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.ten_chuc_vu }}
              </a-select-option>
            </a-select>
          </div>
          <a-button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
            type="primary"
            @click="showAddPersonnelModal = true"
          >
            <Plus class="mr-2 h-4 w-4" />
            Thêm nhân sự
          </a-button>
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
                      <a-tooltip title="Chỉnh sửa">
                        <a-button 
                          type="primary"
                          size="small"
                          @click="editPersonnel(person)"
                        >
                          <Edit class="h-4 w-4" />
                        </a-button>
                      </a-tooltip>
                      <a-tooltip title="Xóa">
                        <a-popconfirm
                          title="Xác nhận xóa"
                          :description="`Bạn có chắc chắn muốn xóa nhân sự ${person.ho_ten} không?`"
                          ok-text="Có"
                          cancel-text="Không"
                          @confirm="confirmDeletePersonnel(person)"
                        >
                          <a-button 
                            type="primary"
                            danger
                            size="small"
                          >
                            <Trash2 class="h-4 w-4" />
                          </a-button>
                        </a-popconfirm>
                      </a-tooltip>
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
      <a-modal 
        v-model:open="showAddRoleModal" 
        :title="editingRole ? 'Chỉnh sửa chức vụ' : 'Thêm chức vụ mới'"
        @ok="handleSaveRole"
      >
        <a-form
          :model="roleForm"
          :rules="roleRules"
          ref="roleFormRef"
          layout="vertical"
        >
          <a-form-item 
            label="Tên chức vụ" 
            name="ten_chuc_vu"
          >
            <a-input 
              v-model:value="roleForm.ten_chuc_vu"
              placeholder="Nhập tên chức vụ"
            />
          </a-form-item>
          
          <a-form-item name="tinh_trang">
            <a-checkbox v-model:checked="roleForm.tinh_trang">
              Đang hoạt động
            </a-checkbox>
          </a-form-item>
        </a-form>
      </a-modal>
  
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
      <a-modal 
        v-model:open="showDeleteModal" 
        title="Xác nhận xóa"
        @ok="confirmDelete"
        :confirmLoading="isSubmitting"
      >
        <p>{{ deleteMessage }}</p>
      </a-modal>
  
      <!-- Modal thêm/sửa nhân sự -->
      <a-modal 
        v-model:open="showAddPersonnelModal" 
        :title="editingPersonnel ? 'Chỉnh sửa nhân sự' : 'Thêm nhân sự mới'"
        @ok="handleSavePersonnel"
        :confirmLoading="isSubmitting"
      >
        <a-form
          :model="personnelForm"
          :rules="personnelRules"
          ref="personnelFormRef"
          layout="vertical"
        >
          <a-form-item 
            label="Họ và tên" 
            name="ho_ten"
          >
            <a-input 
              v-model:value="personnelForm.ho_ten"
              placeholder="Nhập họ và tên"
            />
          </a-form-item>
          
          <a-form-item 
            label="Email" 
            name="email"
          >
            <a-input 
              v-model:value="personnelForm.email"
              placeholder="Nhập email"
            />
          </a-form-item>

          <a-form-item 
            label="Số điện thoại" 
            name="so_dien_thoai"
          >
            <a-input 
              v-model:value="personnelForm.so_dien_thoai"
              placeholder="Nhập số điện thoại"
            />
          </a-form-item>

          <a-form-item 
            label="Chức vụ" 
            name="id_chuc_vu"
          >
            <a-select 
              v-model:value="personnelForm.id_chuc_vu"
              placeholder="Chọn chức vụ"
            >
              <a-select-option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.ten_chuc_vu }}
              </a-select-option>
            </a-select>
          </a-form-item>
          
          <a-form-item name="tinh_trang">
            <a-checkbox v-model:checked="personnelForm.tinh_trang">
              Đang làm việc
            </a-checkbox>
          </a-form-item>
        </a-form>
      </a-modal>
    </div>
  </template>
  
  <script>
  import { 
    Plus, 
    Search, 
    Edit, 
    Trash2, 
    Shield, 
    X, 
    Check 
  } from 'lucide-vue-next'
  import { 
    Modal, 
    Button, 
    Select, 
    Popconfirm, 
    Tooltip, 
    Form, 
    Input, 
    Checkbox 
  } from 'ant-design-vue'
  import baseRequest from '../../core/baseRequest'
  import { useNotificationStore } from '../../stores/notication'
  
  export default {
    name: 'PhanQuyen',
    components: {
      Plus,
      Search,
      Edit,
      Trash2,
      Shield,
      X,
      Check,
      AModal: Modal,
      AButton: Button,
      ASelect: Select,
      ASelectOption: Select.Option,
      APopconfirm: Popconfirm,
      ATooltip: Tooltip,
      AForm: Form,
      AFormItem: Form.Item,
      AInput: Input,
      ACheckbox: Checkbox
    },
    data() {
      return {
        // Tabs
        tabs: [
          { id: 'roles', name: 'Chức vụ' },
          { id: 'permissions', name: 'Chức năng' },
          { id: 'role-permissions', name: 'Phân quyền' },
          { id: 'personnel', name: 'Nhân sự' }
        ],
        activeTab: 'roles',

        // Dữ liệu
        roles: [],
        permissions: [],
        rolePermissions: [],

        // Tìm kiếm và lọc
        roleSearch: '',
        roleStatusFilter: 'all',
        permissionSearch: '',
        permissionStatusFilter: 'all',
        rolePermissionSearch: '',

        // Modals
        showAddRoleModal: false,
        showAddPermissionModal: false,
        showDeleteModal: false,
        deleteMessage: '',
        deleteCallback: null,

        // Forms
        roleForm: {
          ten_chuc_vu: '',
          tinh_trang: true
        },
        permissionForm: {
          ten_chuc_nang: '',
          tinh_trang: true
        },
        editingRole: null,
        editingPermission: null,

        // Phân quyền
        selectedRoleId: '',
        selectedPermissions: [],

        // Dữ liệu nhân sự
        personnel: [],
        personnelSearch: '',
        personnelStatusFilter: 'all',
        personnelRoleFilter: 'all',
        showAddPersonnelModal: false,
        editingPersonnel: null,
        personnelForm: {
          ho_ten: '',
          email: '',
          so_dien_thoai: '',
          id_chuc_vu: '',
          tinh_trang: true
        },
        roleRules: {
          ten_chuc_vu: [
            { required: true, message: 'Vui lòng nhập tên chức vụ', trigger: 'blur' },
            { min: 2, max: 50, message: 'Tên chức vụ phải từ 2-50 ký tự', trigger: 'blur' }
          ]
        },
        roleFormRef: null,
        personnelRules: {
          ho_ten: [
            { required: true, message: 'Vui lòng nhập họ và tên', trigger: 'blur' },
            { min: 2, max: 50, message: 'Họ và tên phải từ 2-50 ký tự', trigger: 'blur' }
          ],
          email: [
            { required: true, message: 'Vui lòng nhập email', trigger: 'blur' },
            { type: 'email', message: 'Email không hợp lệ', trigger: 'blur' }
          ],
          so_dien_thoai: [
            { required: true, message: 'Vui lòng nhập số điện thoại', trigger: 'blur' },
            { pattern: /^\d{10}$/, message: 'Số điện thoại phải có 10 chữ số', trigger: 'blur' }
          ],
          id_chuc_vu: [
            { required: true, message: 'Vui lòng chọn chức vụ', trigger: 'change' }
          ]
        },
        personnelFormRef: null,
        isSubmitting: false
      }
    },

    computed: {
      filteredRoles() {
        let result = [...this.roles]
        
        // Lọc theo tìm kiếm
        if (this.roleSearch) {
          const searchLower = this.roleSearch.toLowerCase()
          result = result.filter(role => 
            role.ten_chuc_vu.toLowerCase().includes(searchLower)
          )
        }
        
        // Lọc theo trạng thái
        if (this.roleStatusFilter !== 'all') {
          const isActive = this.roleStatusFilter === 'active'
          result = result.filter(role => role.tinh_trang === isActive)
        }
        
        return result
      },

      filteredPermissions() {
        let result = [...this.permissions]
        
        // Lọc theo tìm kiếm
        if (this.permissionSearch) {
          const searchLower = this.permissionSearch.toLowerCase()
          result = result.filter(permission => 
            permission.ten_chuc_nang.toLowerCase().includes(searchLower)
          )
        }
        
        // Lọc theo trạng thái
        if (this.permissionStatusFilter !== 'all') {
          const isActive = this.permissionStatusFilter === 'active'
          result = result.filter(permission => permission.tinh_trang === isActive)
        }
        
        return result
      },

      filteredRolePermissions() {
        let result = [...this.permissions]
        
        // Lọc theo tìm kiếm
        if (this.rolePermissionSearch) {
          const searchLower = this.rolePermissionSearch.toLowerCase()
          result = result.filter(permission => 
            permission.ten_chuc_nang.toLowerCase().includes(searchLower)
          )
        }
        
        return result
      },

      selectedRoleName() {
        const role = this.roles.find(r => r.id === this.selectedRoleId)
        return role ? role.ten_chuc_vu : ''
      },

      filteredPersonnel() {
        let result = [...this.personnel]
        
        // Lọc theo tìm kiếm
        if (this.personnelSearch) {
          const searchLower = this.personnelSearch.toLowerCase()
          result = result.filter(person => 
            person.ho_ten.toLowerCase().includes(searchLower) ||
            person.email.toLowerCase().includes(searchLower) ||
            person.so_dien_thoai.includes(searchLower)
          )
        }
        
        // Lọc theo trạng thái
        if (this.personnelStatusFilter !== 'all') {
          const isActive = this.personnelStatusFilter === 'active'
          result = result.filter(person => person.tinh_trang === isActive)
        }

        // Lọc theo chức vụ
        if (this.personnelRoleFilter !== 'all') {
          result = result.filter(person => person.id_chuc_vu === this.personnelRoleFilter)
        }
        
        return result
      }
    },

    mounted() {
      this.fetchData()
    },

    methods: {
      async fetchData() {
        try {
          // Trong thực tế, bạn sẽ gọi API để lấy dữ liệu
          // Ở đây chúng ta sẽ sử dụng dữ liệu mẫu
          this.roles = [
            { id: 1, ten_chuc_vu: 'Admin', tinh_trang: true },
            { id: 2, ten_chuc_vu: 'Quản lý', tinh_trang: true },
            { id: 3, ten_chuc_vu: 'Nhân viên', tinh_trang: true },
            { id: 4, ten_chuc_vu: 'Khách hàng', tinh_trang: false }
          ]
          
          this.permissions = [
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
          
          this.rolePermissions = [
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
          
          this.personnel = [
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
      },

      editRole(role) {
        this.editingRole = role
        this.roleForm = {
          ten_chuc_vu: role.ten_chuc_vu,
          tinh_trang: role.tinh_trang
        }
        this.showAddRoleModal = true
      },

      closeRoleModal() {
        this.showAddRoleModal = false
        this.editingRole = null
        this.roleForm = {
          ten_chuc_vu: '',
          tinh_trang: true
        }
      },

      handleSaveRole() {
        this.roleFormRef.validate().then(() => {
          this.saveRole();
        }).catch(error => {
          console.log('Validation failed:', error);
        });
      },

      saveRole() {
        const notificationStore = useNotificationStore();
        this.isSubmitting = true;

        const url = this.editingRole 
          ? "admin/phan-quyen/cap-nhat-chuc-vu"
          : "admin/phan-quyen/them-chuc-vu";

        baseRequest.post(url, this.roleForm)
          .then((response) => {
            if (response.data.status) {
              this.getRoles();
              this.showAddRoleModal = false;
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Thao tác thất bại');
            }
          })
          .catch((res) => {
            const errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          })
          .finally(() => {
            this.isSubmitting = false;
          });
      },

      async getRoles() {
        const notificationStore = useNotificationStore();
        try {
          const response = await baseRequest.get("admin/phan-quyen/lay-danh-sach-chuc-vu");
          if (response.data.status) {
            this.roles = response.data.data;
          } else {
            notificationStore.showError('Lấy danh sách chức vụ thất bại');
          }
        } catch (error) {
          const errors = Object.values(error.response.data.errors);
          notificationStore.showError(errors[0]);
        }
      },

      editPermission(permission) {
        this.editingPermission = permission
        this.permissionForm = {
          ten_chuc_nang: permission.ten_chuc_nang,
          tinh_trang: permission.tinh_trang
        }
        this.showAddPermissionModal = true
      },

      closePermissionModal() {
        this.showAddPermissionModal = false
        this.editingPermission = null
        this.permissionForm = {
          ten_chuc_nang: '',
          tinh_trang: true
        }
      },

      savePermission() {
        if (this.editingPermission) {
          // Cập nhật chức năng
          const index = this.permissions.findIndex(p => p.id === this.editingPermission.id)
          if (index !== -1) {
            this.permissions[index] = {
              ...this.permissions[index],
              ten_chuc_nang: this.permissionForm.ten_chuc_nang,
              tinh_trang: this.permissionForm.tinh_trang
            }
          }
        } else {
          // Thêm chức năng mới
          const newId = Math.max(0, ...this.permissions.map(p => p.id)) + 1
          this.permissions.push({
            id: newId,
            ten_chuc_nang: this.permissionForm.ten_chuc_nang,
            tinh_trang: this.permissionForm.tinh_trang
          })
        }
        
        this.closePermissionModal()
      },

      deleteRole(role) {
        this.deleteMessage = `Bạn có chắc chắn muốn xóa chức vụ "${role.ten_chuc_vu}" không?`
        this.deleteCallback = () => {
          this.roles = this.roles.filter(r => r.id !== role.id)
          // Xóa các phân quyền liên quan
          this.rolePermissions = this.rolePermissions.filter(rp => rp.id_chuc_vu !== role.id)
        }
        this.showDeleteModal = true
      },

      deletePermission(permission) {
        this.deleteMessage = `Bạn có chắc chắn muốn xóa chức năng "${permission.ten_chuc_nang}" không?`
        this.deleteCallback = () => {
          this.permissions = this.permissions.filter(p => p.id !== permission.id)
          // Xóa các phân quyền liên quan
          this.rolePermissions = this.rolePermissions.filter(rp => rp.id_chuc_nang !== permission.id)
        }
        this.showDeleteModal = true
      },

      confirmDelete() {
        if (this.deleteCallback) {
          this.deleteCallback()
        }
        this.showDeleteModal = false
        this.deleteCallback = null
      },

      manageRolePermissions(role) {
        this.selectedRoleId = role.id
        this.loadRolePermissions()
        this.activeTab = 'role-permissions'
      },

      loadRolePermissions() {
        const rolePerms = this.rolePermissions.filter(rp => 
          rp.id_chuc_vu === this.selectedRoleId && rp.tinh_trang
        )
        this.selectedPermissions = rolePerms.map(rp => rp.id_chuc_nang)
      },

      selectAllPermissions() {
        this.selectedPermissions = this.permissions
          .filter(p => p.tinh_trang)
          .map(p => p.id)
      },

      deselectAllPermissions() {
        this.selectedPermissions = []
      },

      saveRolePermissions() {
        const notificationStore = useNotificationStore();
        this.isSubmitting = true;

        baseRequest.post("admin/phan-quyen/luu-phan-quyen", {
          id_chuc_vu: this.selectedRoleId,
          danh_sach_quyen: this.selectedPermissions
        })
          .then((response) => {
            if (response.data.status) {
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Lưu phân quyền thất bại');
            }
          })
          .catch((res) => {
            const errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          })
          .finally(() => {
            this.isSubmitting = false;
          });
      },

      getRoleName(roleId) {
        const role = this.roles.find(r => r.id === roleId)
        return role ? role.ten_chuc_vu : ''
      },

      editPersonnel(person) {
        this.editingPersonnel = person
        this.personnelForm = {
          ho_ten: person.ho_ten,
          email: person.email,
          so_dien_thoai: person.so_dien_thoai,
          id_chuc_vu: person.id_chuc_vu,
          tinh_trang: person.tinh_trang
        }
        this.showAddPersonnelModal = true
      },

      closePersonnelModal() {
        this.showAddPersonnelModal = false
        this.editingPersonnel = null
        this.personnelForm = {
          ho_ten: '',
          email: '',
          so_dien_thoai: '',
          id_chuc_vu: '',
          tinh_trang: true
        }
      },

      handleSavePersonnel() {
        this.personnelFormRef.validate().then(() => {
          this.savePersonnel();
        }).catch(error => {
          console.log('Validation failed:', error);
        });
      },

      savePersonnel() {
        const notificationStore = useNotificationStore();
        this.isSubmitting = true;

        const url = this.editingPersonnel 
          ? "admin/phan-quyen/cap-nhat-nhan-su"
          : "admin/phan-quyen/them-nhan-su";

        baseRequest.post(url, this.personnelForm)
          .then((response) => {
            if (response.data.status) {
              this.getPersonnel();
              this.showAddPersonnelModal = false;
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Thao tác thất bại');
            }
          })
          .catch((res) => {
            const errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          })
          .finally(() => {
            this.isSubmitting = false;
          });
      },

      async getPersonnel() {
        const notificationStore = useNotificationStore();
        try {
          const response = await baseRequest.get("admin/phan-quyen/lay-danh-sach-nhan-su");
          if (response.data.status) {
            this.personnel = response.data.data;
          } else {
            notificationStore.showError('Lấy danh sách nhân sự thất bại');
          }
        } catch (error) {
          const errors = Object.values(error.response.data.errors);
          notificationStore.showError(errors[0]);
        }
      },

      confirmDeletePersonnel(person) {
        const notificationStore = useNotificationStore();
        this.isSubmitting = true;

        baseRequest.post("admin/phan-quyen/xoa-nhan-su", { id: person.id })
          .then((response) => {
            if (response.data.status) {
              this.getPersonnel();
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Xóa nhân sự thất bại');
            }
          })
          .catch((res) => {
            const errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          })
          .finally(() => {
            this.isSubmitting = false;
          });
      }
    },

    watch: {
      selectedRoleId(newValue) {
        if (newValue) {
          this.loadRolePermissions()
        } else {
          this.selectedPermissions = []
        }
      }
    }
  }
  </script>