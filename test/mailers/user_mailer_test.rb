require 'test_helper'

class UserMailerTest < ActionMailer::TestCase
  test "lesson_request" do
    mail = UserMailer.lesson_request
    assert_equal "Lesson request", mail.subject
    assert_equal ["to@example.org"], mail.to
    assert_equal ["from@example.com"], mail.from
    assert_match "Hi", mail.body.encoded
  end
end
