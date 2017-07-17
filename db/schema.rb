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

ActiveRecord::Schema.define(version: 20170717224503) do

  create_table "attachments", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string   "url"
    t.integer  "lesson_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "authors", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string   "name"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "cal_standards", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer "grade_id",                  default: 0,  null: false
    t.string  "standard_id", limit: 50,    default: "", null: false
    t.text    "description", limit: 65535,              null: false
  end

  create_table "collections", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string "name",            default: "", null: false
    t.string "code", limit: 50, default: "", null: false
  end

  create_table "data_cal_standards", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.datetime "created_at",      null: false
    t.datetime "updated_at",      null: false
    t.integer  "image_id"
    t.integer  "cal_standard_id"
  end

  create_table "data_nat_standards", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.datetime "created_at",      null: false
    t.datetime "updated_at",      null: false
    t.integer  "image_id"
    t.integer  "nat_standard_id"
  end

  create_table "image_authors", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer  "image_id"
    t.integer  "author_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "images", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string  "thumbnail",        limit: 128,   default: "", null: false
    t.string  "title",                          default: "", null: false
    t.text    "card",             limit: 65535
    t.text    "citation",         limit: 65535
    t.integer "collection_id",                  default: 0,  null: false
    t.integer "public",                         default: 0,  null: false
    t.integer "featured",                       default: 0,  null: false
    t.text    "notes",            limit: 65535
    t.text    "original",         limit: 65535
    t.string  "preview"
    t.integer "preview_width"
    t.integer "preview_height"
    t.integer "thumbnail_width"
    t.integer "thumbnail_height"
    t.integer "original_width"
    t.integer "original_height"
    t.index ["title"], name: "idx_images_searchable", using: :btree
  end

  create_table "keyword_assignments", id: false, force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer "sid", default: 0, null: false
    t.integer "kid", default: 0, null: false
  end

  create_table "keywords", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string "title", limit: 64, default: "", null: false
    t.index ["title"], name: "idx_keywords_title", unique: true, using: :btree
  end

  create_table "lesson_authors", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer  "author_id"
    t.integer  "lesson_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "lesson_images", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer  "lesson_id"
    t.integer  "image_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "lessons", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string   "grade"
    t.string   "title"
    t.string   "background"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.string   "pdf"
  end

  create_table "nat_standards", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer "era",                    default: 0, null: false
    t.integer "us_world",               default: 0, null: false
    t.text    "title",    limit: 65535,             null: false
  end

  create_table "region_assignments", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer "image_id",  default: 0, null: false
    t.integer "region_id", default: 0, null: false
  end

  create_table "regions", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string "title", limit: 64, default: "", null: false
  end

  create_table "topic_assignments", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.integer "image_id", default: 0, null: false
    t.integer "topic_id", default: 0, null: false
  end

  create_table "topics", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string  "code",       limit: 3,  default: "",   null: false
    t.string  "title",      limit: 50, default: "",   null: false
    t.string  "collection", limit: 2,  default: "US", null: false
    t.integer "featured",              default: 0,    null: false
  end

end
