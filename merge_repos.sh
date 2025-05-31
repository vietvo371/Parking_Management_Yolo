#!/bin/bash

set -e

# Thư mục tạm
TEMP_DIR=temp_merge
REPO_MAIN_PATH=$(pwd)

# Cleanup từ lần chạy trước (nếu có)
echo "🧹  Cleanup từ lần chạy trước..."
rm -rf "$TEMP_DIR"

# Danh sách repo phụ
REPO_URLS=(
  # "git@github.com:vietvo371/MovieStreaming_Fe.git"
  # "git@github.com:vietvo371/MovieStreaming_Be.git"
  "git@github.com:vietvo371/QL_Nha_Xe_Fe.git"
)

# Tên thư mục tương ứng
FOLDERS=(
  # "DATN_FE"
  # "DATN_BE"
  "Parking_Fe"
)

# Xóa các remote cũ nếu có
for folder in "${FOLDERS[@]}"; do
  if git remote | grep -q "${folder}_remote"; then
    echo "🧹  Xóa remote cũ ${folder}_remote..."
    git remote remove "${folder}_remote"
  fi
done

# Tạo thư mục tạm và xử lý từng repo
mkdir -p "$TEMP_DIR"
cd "$TEMP_DIR" || exit 1

for i in "${!REPO_URLS[@]}"; do
  repo_url="${REPO_URLS[$i]}"
  folder="${FOLDERS[$i]}"

  echo "➡️  Cloning $repo_url vào thư mục $folder ..."
  git clone "$repo_url" "$folder" || { echo "❌ Clone thất bại"; exit 1; }
  cd "$folder" || exit 1

  echo "📦  Di chuyển toàn bộ lịch sử vào thư mục $folder ..."
  git filter-repo --to-subdirectory-filter "$folder"

  echo "🔗  Trở lại repo chính để merge..."
  cd "$REPO_MAIN_PATH" || exit 1
  git remote add "${folder}_remote" "$REPO_MAIN_PATH/$TEMP_DIR/$folder"
  git fetch "${folder}_remote"

  echo "🔀  Merge $folder vào nhánh hiện tại..."
  git merge --allow-unrelated-histories "${folder}_remote/main" -m "Merge $folder vào monorepo"

  echo "🧹  Xoá remote tạm ${folder}_remote ..."
  git remote remove "${folder}_remote"

  cd "$TEMP_DIR" || exit 1
done

# Dọn dẹp
cd "$REPO_MAIN_PATH" || exit 1
rm -rf "$TEMP_DIR"

echo "✅ Hoàn tất merge tất cả repo vào GIT_DATN"
