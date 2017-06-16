# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20170616194402) do

  create_table "attachments", force: :cascade do |t|
    t.string   "url"
    t.integer  "lesson_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "authors", force: :cascade do |t|
    t.string   "name"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "cal_standards", force: :cascade do |t|
    t.integer "grade_id",               default: 0,  null: false
    t.string  "standard_id", limit: 50, default: "", null: false
    t.text    "description",                         null: false
  end

  create_table "collections", force: :cascade do |t|
    t.string "name", limit: 255, default: "", null: false
    t.string "code", limit: 50,  default: "", null: false
  end

  create_table "data_cal_standards", force: :cascade do |t|
    t.datetime "created_at",      null: false
    t.datetime "updated_at",      null: false
    t.integer  "image_id"
    t.integer  "cal_standard_id"
  end

  create_table "data_nat_standards", force: :cascade do |t|
    t.datetime "created_at",      null: false
    t.datetime "updated_at",      null: false
    t.integer  "image_id"
    t.integer  "nat_standard_id"
  end

  create_table "image_authors", force: :cascade do |t|
    t.integer  "image_id"
    t.integer  "author_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "images", force: :cascade do |t|
    t.string  "file",          limit: 96,  default: "", null: false
    t.string  "thumbnail",     limit: 128, default: "", null: false
    t.string  "title",         limit: 255, default: "", null: false
    t.string  "card",                      default: "", null: false
    t.string  "citation",                  default: "", null: false
    t.integer "collection_id",             default: 0,  null: false
    t.integer "public",                    default: 0,  null: false
    t.integer "views",                     default: 0,  null: false
    t.integer "featured",                  default: 0,  null: false
    t.string  "notes",                     default: "", null: false
    t.text    "s3"
    t.string  "view"
    t.integer "start_year"
    t.integer "end_year"
    t.index ["title", "card", "citation", "notes"], name: "idx_images_searchable"
  end

  create_table "keyword_assignments", primary_key: ["sid", "kid"], force: :cascade do |t|
    t.integer "sid", default: 0, null: false
    t.integer "kid", default: 0, null: false
    t.index ["sid", "kid"], name: "sqlite_autoindex_keyword_assignments_1", unique: true
  end

  create_table "keywords", force: :cascade do |t|
    t.string "title", limit: 64, default: "", null: false
    t.index ["title"], name: "sqlite_autoindex_keywords_1", unique: true
  end

  create_table "lesson_authors", force: :cascade do |t|
    t.integer  "author_id"
    t.integer  "lesson_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "lesson_images", force: :cascade do |t|
    t.integer  "lesson_id"
    t.integer  "image_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "lessons", force: :cascade do |t|
    t.string   "grade"
    t.string   "title"
    t.string   "background"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string   "pdf"
  end

  create_table "nat_standards", force: :cascade do |t|
    t.integer "era",      default: 0, null: false
    t.integer "us_world", default: 0, null: false
    t.text    "title",                null: false
  end

  create_table "region_assignments", force: :cascade do |t|
    t.integer "image_id",  default: 0, null: false
    t.integer "region_id", default: 0, null: false
  end

  create_table "regions", force: :cascade do |t|
    t.string "title", limit: 64, default: "", null: false
  end

  create_table "topic_assignments", force: :cascade do |t|
    t.integer "image_id", default: 0, null: false
    t.integer "topic_id", default: 0, null: false
  end

  create_table "topics", force: :cascade do |t|
    t.string  "code",       limit: 3,  default: "",   null: false
    t.string  "title",      limit: 50, default: "",   null: false
    t.text    "collection",            default: "US", null: false
    t.integer "featured",              default: 0,    null: false
  end

end
